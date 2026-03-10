# Late Payments Tracking System

A comprehensive payment schedule and late payment tracking system integrated with the venue management platform.

## Overview

This system provides:

- **Payment Schedule Management**: Create payment schedules from predefined templates or custom configurations
- **Installment Tracking**: Track payments against installments with automatic status updates
- **Late Payment Detection**: Automated daily checks for overdue payments
- **Alert System**: Multi-severity alerts (low, medium, high, critical) with escalation workflows
- **Notifications**: Automated email reminders and overdue notices
- **Financial Dashboard**: Real-time metrics and reporting

## Features

### Payment Schedule Templates

| Template | Structure | Description |
|----------|-----------|-------------|
| Standard 3-Tier | 30/40/30 | Deposit (60 days before), Interim (30 days before), Final (7 days before) |
| Equal Split | 50/50 | Deposit and final payment |
| Full Upfront | 100% | Full payment required at booking |
| Corporate 60-Day | Net 60 | Payment due 60 days after event |
| Monthly 6 | 6 payments | Spread over 6 months |

### Severity Levels

| Severity | Days Overdue | Action |
|----------|--------------|--------|
| Low | 1-7 | Internal notification |
| Medium | 8-14 | Client notification |
| High | 15-30 | Urgent client notice |
| Critical | 30+ | Final notice, escalation |

## Installation

### 1. Run Migrations

```bash
php artisan migrate
```

### 2. Seed Default Templates

```bash
php artisan db:seed --class=PaymentScheduleTemplatesSeeder
```

### 3. Optional: Seed Sample Data

```bash
php artisan db:seed --class=PaymentSchedulesSeeder
php artisan db:seed --class=LatePaymentAlertsSeeder
```

### 4. Or Seed All

```bash
php artisan db:seed
```

## Scheduled Commands

The following commands are automatically scheduled in `app/Console/Kernel.php`:

| Command | Schedule | Description |
|---------|----------|-------------|
| `payments:check-late` | Daily at 6:00 AM | Check for overdue payments and create alerts |
| `payments:send-reminders --days=7` | Daily at 9:00 AM | Send reminders for payments due in 7 days |
| `payments:send-reminders --days=1` | Daily at 10:00 AM | Send final reminders for next-day payments |

## Manual Commands

### Check for Late Payments

```bash
# Run check
php artisan payments:check-late

# Dry run (no alerts created)
php artisan payments:check-late --dry-run

# Auto-resolve paid installments
php artisan payments:check-late --auto-resolve

# Check specific client
php artisan payments:check-late --client=123
```

### Send Payment Reminders

```bash
# Send reminders for payments due in 7 days
php artisan payments:send-reminders --days=7

# Send to specific client
php artisan payments:send-reminders --days=7 --client=123

# Dry run
php artisan payments:send-reminders --days=7 --dry-run
```

## API Endpoints

### Payment Schedules

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/payment-schedules` | List all schedules |
| POST | `/payment-schedules/from-template` | Create from template |
| POST | `/payment-schedules/custom` | Create custom schedule |
| GET | `/payment-schedules/{id}` | Get schedule details |
| POST | `/payment-schedules/{id}/activate` | Activate schedule |
| POST | `/payment-schedules/{id}/payment` | Record payment |
| DELETE | `/payment-schedules/{id}` | Delete schedule |
| GET | `/payment-schedules/templates` | List templates |
| GET | `/payment-schedules/upcoming` | Get upcoming payments |
| GET | `/payment-schedules/dashboard` | Get dashboard data |
| GET | `/payment-schedules/reservation/{id}` | Get by reservation |

### Late Payment Alerts

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/late-payment-alerts` | List all alerts |
| GET | `/late-payment-alerts/active` | Get active alerts |
| GET | `/late-payment-alerts/high-priority` | Get high/critical alerts |
| GET | `/late-payment-alerts/{id}` | Get alert details |
| POST | `/late-payment-alerts/{id}/acknowledge` | Acknowledge alert |
| POST | `/late-payment-alerts/{id}/resolve` | Resolve alert |
| POST | `/late-payment-alerts/{id}/escalate` | Escalate alert |
| GET | `/late-payment-alerts/statistics` | Get statistics |
| GET | `/late-payment-alerts/summary` | Get dashboard summary |
| GET | `/late-payment-alerts/run-check` | Run manual check |

## Usage Examples

### Creating a Payment Schedule

```php
use App\Modules\Payments\Services\PaymentScheduleService;
use App\Modules\Reservations\Models\Reservation;

$service = app(PaymentScheduleService::class);
$reservation = Reservation::find(1);

// From template
$schedule = $service->createFromTemplate(
    $reservation,
    'standard_3_tier',
    10000.00,
    auth()->id()
);

// Custom schedule
$schedule = $service->createCustom(
    $reservation,
    [
        ['name' => 'Deposit', 'type' => 'deposit', 'percentage' => 30, 'due_date' => '2026-05-01'],
        ['name' => 'Final', 'type' => 'final', 'percentage' => 70, 'due_date' => '2026-06-01'],
    ],
    10000.00,
    auth()->id()
);
```

### Recording a Payment

```php
$result = $service->recordPayment(
    $schedule,
    3000.00,
    $installmentId, // optional, specific installment
    [
        'payment_method' => 2, // Bank transfer
        'transaction_reference' => 'TRX-123456',
        'notes' => 'Initial deposit',
        'date' => '2026-03-15',
    ]
);
```

### Checking for Late Payments

```php
use App\Modules\Payments\Services\LatePaymentService;

$service = app(LatePaymentService::class);
$result = $service->checkForLatePayments();

// Returns:
// [
//     'checked' => 50,
//     'newly_overdue' => 5,
//     'alerts_created' => 3,
//     'alerts_updated' => 2,
// ]
```

### Managing Alerts

```php
// Acknowledge
$service->acknowledgeAlert($alertId, auth()->id());

// Resolve
$service->resolveAlert($alertId, 'Payment received in full');

// Escalate
$service->escalateAlert($alertId, $managerId, 'Requires management attention');
```

### Dashboard Data

```php
use App\Modules\Payments\Services\FinancialDashboardService;

$service = app(FinancialDashboardService::class);
$data = $service->getDashboardData();

// Returns:
// [
//     'summary' => [...],
//     'payment_status' => [...],
//     'upcoming_payments' => [...],
//     'overdue_payments' => [...],
//     'monthly_trend' => [...],
//     'top_clients' => [...],
//     'late_payment_stats' => [...],
// ]
```

## Events

| Event | Description |
|-------|-------------|
| `PaymentScheduleCreated` | Fired when a new schedule is created |
| `PaymentReceived` | Fired when a payment is recorded |
| `PaymentOverdue` | Fired when an installment becomes overdue |

## Notifications

| Notification | When Sent |
|--------------|-----------|
| `PaymentReminderNotification` | 7 days before due date |
| `PaymentOverdueNotification` | When payment is overdue |
| `PaymentFinalNoticeNotification` | When 15+ days overdue |

## Database Schema

### payment_schedules
- Core payment schedule information
- Links to reservation and client
- Tracks template type, totals, status
- Grace period and late fee settings

### payment_installments
- Individual payment installments
- Due dates, amounts, paid amounts
- Status tracking (pending, partial, paid, overdue)
- Late fee tracking

### late_payment_alerts
- Alert records for overdue payments
- Severity levels and status
- Acknowledgment and escalation tracking

### payment_schedule_templates
- Predefined payment templates
- JSON configuration for installments
- Default settings per template type

## Configuration

### Late Fee Calculation

Late fees are calculated as:
- Monthly compounding (every 30 days)
- Formula: `outstanding_amount × percentage × periods`
- Optional cap on maximum late fees

### Grace Period

- Configurable per schedule/template
- No alerts created until grace period expires
- Default: 3 days for standard template

## Testing

Run the seeders to create sample data:

```bash
php artisan db:seed --class=PaymentScheduleTemplatesSeeder
php artisan db:seed --class=PaymentSchedulesSeeder
php artisan db:seed --class=LatePaymentAlertsSeeder
```

Then test the commands:

```bash
php artisan payments:check-late --dry-run
php artisan payments:send-reminders --days=7 --dry-run
```

## Troubleshooting

### Alerts not being created
- Check if grace period has passed
- Verify schedule status is 'active'
- Ensure installment status is 'pending'

### Notifications not sending
- Check mail configuration
- Verify client has email address
- Check queue worker is running (if using queue)

### Scheduled commands not running
- Ensure cron job is configured: `* * * * * cd /path && php artisan schedule:run >> /dev/null 2>&1`
- Check Laravel scheduler logs
