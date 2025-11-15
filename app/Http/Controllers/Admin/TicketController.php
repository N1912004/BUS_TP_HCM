<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::with('user')->get();
        return view('backend.admin.tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $users = User::where('role', 'user')->get(['id', 'fullname']);

        return view('backend.admin.tickets.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'ticket_type' => 'required|in:student,regular,elderly',
            'has_student_card' => 'nullable|boolean',
            'age' => 'nullable|integer|min:0',
        ]);

        $price = $this->calculateTicketPrice(
            $validatedData['ticket_type'],
            $validatedData['has_student_card'] ?? false,
            $validatedData['age'] ?? null
        );

        Ticket::create([
            'user_id' => $validatedData['user_id'],
            'ticket_type' => $validatedData['ticket_type'],
            'price' => $price,
            'has_student_card' => $validatedData['has_student_card'] ?? false,
            'age' => $validatedData['age'] ?? null,
        ]);

        return redirect()->route('admin.tickets.index')->with('success', 'Vé đã được tạo thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('admin.tickets.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        $users = User::all();
        return view('backend.admin.tickets.edit', compact('ticket', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'ticket_type' => 'required|in:student,regular,elderly',
            'has_student_card' => 'nullable|boolean',
            'age' => 'nullable|integer|min:0',
        ]);

        $price = $this->calculateTicketPrice(
            $validatedData['ticket_type'],
            $validatedData['has_student_card'] ?? false,
            $validatedData['age'] ?? null
        );

        $ticket->update([
            'user_id' => $validatedData['user_id'],
            'ticket_type' => $validatedData['ticket_type'],
            'price' => $price,
            'has_student_card' => $validatedData['has_student_card'] ?? false,
            'age' => $validatedData['age'] ?? null,
        ]);

        return redirect()->route('admin.tickets.index')->with('success', 'Vé đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('admin.tickets.index')->with('success', 'Vé đã được xóa thành công!');
    }

    /**
     * Calculate ticket price based on type and conditions.
     */
    private function calculateTicketPrice(string $ticketType, bool $hasStudentCard, ?int $age): float
    {
        switch ($ticketType) {
            case 'student':
                return $hasStudentCard ? 3000.00 : 6000.00;
            case 'regular':
                return 6000.00;
            case 'elderly':
                return ($age !== null && $age > 65) ? 0.00 : 6000.00;
            default:
                return 6000.00; // Default price
        }
    }
}
