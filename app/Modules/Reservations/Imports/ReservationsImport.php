<?php

namespace App\Modules\Reservations\Imports;

use App\Modules\Reservations\Models\Reservation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class ReservationsImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows, SkipsOnError
{
    use Importable, SkipsErrors;

    /**
     * Map status strings to their corresponding integer values
     */
    protected $statusMap = [
        'pending' => 0,
        'confirmed' => 1,
        'completed' => 2,
        'cancelled' => 3
    ];
    
    /**
     * Store missing references for reporting
     */
    protected $missingVenues = [];
    protected $missingClients = [];
    protected $missingMenus = [];
    protected $missingManagers = [];
    
    /**
     * Get missing references
     */
    public function getMissingVenues()
    {
        return $this->missingVenues;
    }
    
    public function getMissingClients()
    {
        return $this->missingClients;
    }
    
    public function getMissingMenus()
    {
        return $this->missingMenus;
    }
    
    public function getMissingManagers()
    {
        return $this->missingManagers;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Skip rows that don't have all required fields
        if (empty($row['venue_id']) || empty($row['client_id']) || empty($row['menu_id']) || 
            empty($row['manager_id']) || empty($row['menu_price']) || empty($row['date']) || 
            empty($row['reservation_type']) || empty($row['number_of_guests']) || 
            empty($row['current_payment']) || empty($row['total_payment'])) {
            return null;
        }
        
        // Check for missing references
        $this->checkMissingReferences($row);
        
        // Convert status string to integer if provided
        $status = 0; // Default to 'pending' (0)
        if (!empty($row['status'])) {
            if (is_numeric($row['status'])) {
                $status = (int)$row['status'];
            } elseif (isset($this->statusMap[strtolower($row['status'])])) {
                $status = $this->statusMap[strtolower($row['status'])];
            }
        }
        
        return new Reservation([
            'location_id' => auth()->user()->getCurrentLocationId(),
            'venue_id' => $row['venue_id'],
            'client_id' => $row['client_id'],
            'menu_id' => $row['menu_id'],
            'manager_id' => $row['manager_id'],
            'menu_price' => $row['menu_price'],
            'date' => $this->transformDate($row['date']),
            'reservation_type' => $row['reservation_type'],
            'description' => $row['description'] ?? null,
            'number_of_guests' => $row['number_of_guests'],
            'current_payment' => $row['current_payment'],
            'total_payment' => $row['total_payment'],
            'staff_expenses' => $row['staff_expenses'] ?? 0,
            'status' => $status,
        ]);
    }
    
    /**
     * Check for missing references and collect them for reporting
     */
    protected function checkMissingReferences($row)
    {
        // Check venue
        if (!empty($row['venue_id']) && !DB::table('venues')->where('id', $row['venue_id'])->exists()) {
            $rowNum = isset($row['@row']) ? $row['@row'] : 'Unknown';
            $this->missingVenues[$row['venue_id']] = "Row {$rowNum} references venue ID {$row['venue_id']} which doesn't exist";
        }
        
        // Check client
        if (!empty($row['client_id']) && !DB::table('clients')->where('id', $row['client_id'])->exists()) {
            $rowNum = isset($row['@row']) ? $row['@row'] : 'Unknown';
            $this->missingClients[$row['client_id']] = "Row {$rowNum} references client ID {$row['client_id']} which doesn't exist";
        }
        
        // Check menu
        if (!empty($row['menu_id']) && !DB::table('menus')->where('id', $row['menu_id'])->exists()) {
            $rowNum = isset($row['@row']) ? $row['@row'] : 'Unknown';
            $this->missingMenus[$row['menu_id']] = "Row {$rowNum} references menu ID {$row['menu_id']} which doesn't exist";
        }
        
        // Check manager
        if (!empty($row['manager_id']) && !DB::table('users')->where('id', $row['manager_id'])->exists()) {
            $rowNum = isset($row['@row']) ? $row['@row'] : 'Unknown';
            $this->missingManagers[$row['manager_id']] = "Row {$rowNum} references manager ID {$row['manager_id']} which doesn't exist";
        }
    }

    /**
     * Transform a date value into a DateTime object.
     *
     * @param mixed $value
     * @return string
     * @throws \Exception
     */
    public function transformDate($value)
    {
        try {
            if (empty($value)) {
                throw new \Exception("Date cannot be empty");
            }
            
            // If it's a numeric value, treat it as an Excel date
            if (is_numeric($value)) {
                $dateTime = ExcelDate::excelToDateTimeObject($value);
                return $dateTime->format('Y-m-d H:i:s');
            }
            
            // If it's already a DateTime object
            if ($value instanceof \DateTime) {
                return $value->format('Y-m-d H:i:s');
            }
            
            // If it's a string, try to parse it
            if (is_string($value)) {
                // Try different date formats
                $formats = ['Y-m-d', 'd-m-Y', 'm/d/Y', 'd/m/Y', 'Y/m/d'];
                
                foreach ($formats as $format) {
                    $date = \DateTime::createFromFormat($format, $value);
                    if ($date !== false) {
                        return $date->format('Y-m-d H:i:s');
                    }
                }
                
                // If none of the formats worked, try PHP's strtotime
                $timestamp = strtotime($value);
                if ($timestamp !== false) {
                    return date('Y-m-d H:i:s', $timestamp);
                }
            }
            
            // If we got here, we couldn't parse the date
            throw new \Exception("Could not parse date: {$value}");
        } catch (\Exception $e) {
            \Log::error('Date transformation error: ' . $e->getMessage());
            throw new \Exception("Invalid date format: {$value}");
        }
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'venue_id' => 'required|exists:venues,id',
            'client_id' => 'required|exists:clients,id',
            'menu_id' => 'required|exists:menus,id',
            'manager_id' => 'required|exists:users,id',
            'menu_price' => 'required|numeric',
            'date' => 'required',
            'reservation_type' => 'required|in:1,2,3',
            'number_of_guests' => 'required|integer',
            'current_payment' => 'required|numeric',
            'total_payment' => 'required|numeric',
            'staff_expenses' => 'nullable|numeric',
            'description' => 'nullable|string',
            'status' => 'nullable',
        ];
    }
}