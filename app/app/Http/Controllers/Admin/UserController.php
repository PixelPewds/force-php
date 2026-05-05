<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Activitylog\Models\Activity;
use Auth;
use Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->get();
        return view('admin.users.list', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all()->pluck('name');
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|unique:users',
            'password' => 'required',
            'mobile' => 'required|unique:users',
            // 'profile_pic' => 'required|image',
            'role_id' => 'required',
            'gender' => 'required',
            'address' => 'required',
        ]);

        $validated['created_by'] = auth()->id();

        if ($request->has('profile_pic')) {
            $filename = 'user_' . time() . '.' . $request->profile_pic->getClientOriginalExtension();
            $validated['profile_pic'] = $filename;
            $request->profile_pic->storeAs('profile', $filename, 'public');
        }

        $user = User::create($validated);
        $user->assignRole($request->role_id);

        activity()
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->withProperties(['name' => $user->name, 'email' => $user->email])
            ->log('User created successfully');

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function activityLogs()
    {
        $activities = Activity::with('causer') // eager load user
            ->latest()
            ->paginate(10); // adjust pagination as needed

        return view('admin.activity-logs.list', compact('activities'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $role = $user->getRoleNames()[0] ?? "";
        $roles = Role::all()->pluck('name');
        return view('admin.users.edit', compact('user', 'role', 'roles'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|unique:users,email,' . $user->id,
            'mobile' => 'required|unique:users,mobile,' . $user->id,
            // 'profile_pic' => 'image',
            'role_id' => 'required',
            'gender' => 'required',
            'address' => 'required',
        ]);

        $data = $request->all();

        if (empty($data['password'])) {
            unset($data['password']);
        }

        if (!empty($data['profile_pic'])) {
            $filename = 'user_' . time() . '.' . $request->profile_pic->getClientOriginalExtension();
            $data['profile_pic'] = $filename;
            $request->profile_pic->storeAs('profile', $filename, 'public');
        }

        $data['updated_by'] = auth()->id();

        $user->update($data);
        $user->syncRoles([$request->role_id]);

        activity()
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->withProperties(['name' => $user->name, 'email' => $user->email])
            ->log('User updated successfully');

        return redirect()->route('users.index')->with('warning', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        activity()
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->withProperties(['name' => $user->name, 'email' => $user->email])
            ->log('User deleted successfully');

        $user->update(['status' => 'N']);
        return redirect()->route('users.index')->with('danger', 'User ' . $user->name . ' deleted successfully.');

    }

    /**
     * Show the form for bulk uploading users.
     */
    public function bulkUploadForm()
    {
        return view('admin.users.bulk-upload');
    }

    /**
     * Store bulk uploaded users from Excel file.
     */
    public function bulkStore(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120', // 5MB max
        ]);

        try {
            $file = $request->file('file');
            $path = $file->store('student/bulk', 'public');
            // ->store('resources/student', 'public');

            $filePath = public_path('storage/' . $path);

            // Load Excel file
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $data = $worksheet->toArray();

            // Skip header row
            $errors = [];
            $createdCount = 0;
            $studentRole = Role::where('name', 'Student')->first();

            if (!$studentRole) {
                Storage::delete($path);
                return redirect()->back()->with('danger', 'Student role does not exist. Please create it first.');
            }

            // Process each row
            foreach ($data as $index => $row) {
                // Skip header row and empty rows
                if ($index === 0 || !isset($row[0]) || empty($row[0])) {
                    continue;
                }

                try {
                    $name = $row[0] ?? null;
                    $email = $row[1] ?? null;
                    $password = $row[2] ?? null;
                    $mobile = $row[3] ?? null;
                    $gender = $row[4] ?? null;
                    $address = $row[5] ?? null;

                    // Validate required fields
                    if (!$name || !$email || !$password || !$mobile || !$gender || !$address) {
                        $errors[] = "Row " . ($index + 1) . ": Missing required fields";
                        continue;
                    }

                    // Check for existing email
                    if (User::where('email', $email)->exists()) {
                        $errors[] = "Row " . ($index + 1) . ": Email '{$email}' already exists";
                        continue;
                    }

                    // Check for existing mobile
                    if (User::where('mobile', $mobile)->exists()) {
                        $errors[] = "Row " . ($index + 1) . ": Mobile '{$mobile}' already exists";
                        continue;
                    }

                    // Create user
                    $user = User::create([
                        'name' => $name,
                        'email' => $email,
                        'password' => Hash::make($password),
                        'mobile' => $mobile,
                        'gender' => $gender,
                        'address' => $address,
                        'created_by' => auth()->id(),
                        'status' => 'Y',
                    ]);

                    // Assign Student role
                    $user->assignRole($studentRole);

                    // Log activity
                    activity()
                        ->performedOn($user)
                        ->causedBy(auth()->user())
                        ->withProperties(['name' => $user->name, 'email' => $user->email, 'source' => 'bulk_upload'])
                        ->log('User created via bulk upload');

                    $createdCount++;
                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 1) . ": " . $e->getMessage();
                }
            }

            // Delete temp file
            Storage::delete($path);

            $message = "Bulk upload completed. {$createdCount} users created successfully.";
            if (!empty($errors)) {
                $message .= " " . count($errors) . " errors encountered.";
            }

            return redirect()->route('users.index')->with(
                'success',
                $message
            )->with('errors', $errors);
        } catch (\Exception $e) {
            if (isset($path)) {
                Storage::delete($path);
            }
            return redirect()->back()->with('danger', 'Error processing file: ' . $e->getMessage());
        }
    }

    /**
     * Download Excel template for bulk upload.
     */
    public function downloadTemplate()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = ['Name', 'Email', 'Password', 'Mobile', 'Gender', 'Address'];
        foreach ($headers as $col => $header) {
            $sheet->setCellValue(chr(65 + $col) . '1', $header);
        }

        // Add sample data
        $sheet->setCellValue('A2', 'John Doe');
        $sheet->setCellValue('B2', 'john@example.com');
        $sheet->setCellValue('C2', 'Password123');
        $sheet->setCellValue('D2', '1234567890');
        $sheet->setCellValue('E2', 'Male');
        $sheet->setCellValue('F2', '123 Main Street');

        $sheet->setCellValue('A3', 'Jane Smith');
        $sheet->setCellValue('B3', 'jane@example.com');
        $sheet->setCellValue('C3', 'Password456');
        $sheet->setCellValue('D3', '0987654321');
        $sheet->setCellValue('E3', 'Female');
        $sheet->setCellValue('F3', '456 Oak Avenue');

        // Auto-adjust columns
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Format header row
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A1:F1')->applyFromArray($headerStyle);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $fileName = 'student_bulk_upload_template_' . date('Y-m-d_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        $writer->save('php://output');
        exit;
    }
}
