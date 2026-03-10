<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\V1\ExpenseResource;
use App\Modules\Expenses\Models\Expense;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpensesController extends BaseApiController
{
    /**
     * List all expenses with filtering
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'category' => 'nullable|string',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'search' => 'nullable|string|max:100',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $query = Expense::with('user')
            ->orderBy('date', 'desc');

        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }
        if (!empty($filters['date_from'])) {
            $query->whereDate('date', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('date', '<=', $filters['date_to']);
        }
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        $expenses = $query->paginate($filters['per_page'] ?? 15);

        return $this->paginatedResponse(
            ExpenseResource::collection($expenses)
        );
    }

    /**
     * Create a new expense
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'category' => ['nullable', 'string', 'max:100'],
            'date' => ['required', 'date'],
            'reservation_id' => ['nullable', 'exists:reservations,id'],
        ]);

        $validated['user_id'] = $request->user()->id;

        $expense = Expense::create($validated);

        return $this->createdResponse(
            new ExpenseResource($expense->load('user')),
            'Expense created successfully'
        );
    }

    /**
     * Get a single expense
     */
    public function show(Expense $expense): JsonResponse
    {
        return $this->successResponse(
            new ExpenseResource($expense->load('user'))
        );
    }

    /**
     * Update an expense
     */
    public function update(Request $request, Expense $expense): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'amount' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'category' => ['sometimes', 'nullable', 'string', 'max:100'],
            'date' => ['sometimes', 'date'],
        ]);

        $expense->update($validated);

        return $this->successResponse(
            new ExpenseResource($expense->fresh()->load('user')),
            'Expense updated successfully'
        );
    }

    /**
     * Delete an expense
     */
    public function destroy(Expense $expense): JsonResponse
    {
        $expense->delete();

        return $this->noContentResponse();
    }

    /**
     * Get monthly summary
     */
    public function monthlySummary(Request $request): JsonResponse
    {
        $year = $request->input('year', now()->year);

        $summary = Expense::select(
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(price) as total')
            )
            ->whereYear('date', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return $this->successResponse($summary);
    }

    /**
     * Get expenses by category
     */
    public function byCategory(string $category): JsonResponse
    {
        $expenses = Expense::where('category', $category)
            ->with('user')
            ->orderBy('date', 'desc')
            ->paginate(15);

        return $this->paginatedResponse(
            ExpenseResource::collection($expenses)
        );
    }
}
