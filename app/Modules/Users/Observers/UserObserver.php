<?php

declare(strict_types=1);

namespace App\Modules\Users\Observers;

use App\Models\User;
use App\Modules\Users\Services\UsersService;
use App\Notifications\StaffAddressInformationHasChanged;
use App\Notifications\StaffBankingInformationHasChanged;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class UserObserver {
    public $usersService;


    public function __construct(
        UsersService $usersService
    )
    {
        $this->usersService = $usersService;
    }
    public function created(User $user): void
    {
        if (empty($user->attributes)) {
            $user->attributes = json_encode([
                "address" => "",
                "postcode" => "",
                "city" => "",
                "canton" => "",
                "gender" => "",
                "eye_color" => "",
                "hair_color" => "",
                "height" => null,
                "weight" => null,
                "shoe_size" => null,
                "chest" => null,
                "waist" => null,
                "hip" => null,
                "clothes" => [
                    "blazer" => "",
                    "trousers" => "",
                    "blouse" => "",
                    "skirt" => "",
                    "dress" => "",
                    "t_shirt" => "",
                    "jacket" => "",
                    "shirt" => "",
                ],
                "tattoos" => "",
                "tattoos_description" => "",
                "piercings" => "",
                "piercings_description" => "",
                "profession" => "",
                "current_occupation" => "",
                "education" => "",
                "languages" => [],
                "social_security_no" => "",
                "nationality" => "",
                "date_of_birth" => "",
                "civil_status" => "",
                "children" => "",
                "withholding_tax" => "",
                "drivers_license" => "",
                "vehicle" => "",
                "work_status" => "",
                "bank_name" => "",
                "iban" => "",
                "swift" => "",
                "clearing" => "",
                "experience_and_motivation" => "",
                "experience" => [
                    "host" => null,
                    "car_explainer" => null,
                    "waiter" => null,
                    "bartender" => null,
                    "model" => null,
                ],
                "internal_note" => "",
                "casual_picture" => "",
                "portrait_picture" => "",
                "full_body_picture" => "",
                "instagram" => "",
                "linkedin" => "",
                "facebook" => "",
                "website" => "",
                "additional_info" => "",
                "passport_or_id" => "",
                "cv" => "",
                "other_document" => "",
            ]);

            $user->save();
        }
    }

    public function updated(User $user): void
    {
        $oldAttributes = $user->getOriginal();
        $newAttributes = $user->getDirty();

        // Check if 'iban' is one of the changed attributes within the 'attributes' JSON
//        if (
//            isset($newAttributes['attributes'])
//            && isset($oldAttributes['attributes'])
//            &&
//            (
//                json_decode($newAttributes['attributes'], true)['iban'] !== json_decode($oldAttributes['attributes'], true)['iban'] ||
//                json_decode($newAttributes['attributes'], true)['bank_name'] !== json_decode($oldAttributes['attributes'], true)['bank_name']
//            )
//        ) {
//            Notification::send(
//                $this->usersService->getAll(),
//                new StaffBankingInformationHasChanged($user)
//            );
//        }
//
//        if (
//            isset($newAttributes['attributes'])
//            && isset($oldAttributes['attributes'])
//            &&
//            (
//                json_decode($newAttributes['attributes'], true)['address'] !== json_decode($oldAttributes['attributes'], true)['address'] ||
//                json_decode($newAttributes['attributes'], true)['canton'] !== json_decode($oldAttributes['attributes'], true)['canton'] ||
//                json_decode($newAttributes['attributes'], true)['city'] !== json_decode($oldAttributes['attributes'], true)['city'] ||
//                json_decode($newAttributes['attributes'], true)['postcode'] !== json_decode($oldAttributes['attributes'], true)['postcode']
//            )
//        ) {
//            Notification::send(
//                $this->usersService->getAll(),
//                new StaffAddressInformationHasChanged($user)
//            );
//        }

    }


    public function forceDeleting(User $user): void {
        $files = Storage::files("StaffFiles/CVs/{$user->id}");

        foreach ($files as $file) {
            Storage::delete($file);
        }

        $pdfFilePath = "StaffPDF/{$user->id}-user-sedcard.pdf";

        if (Storage::exists($pdfFilePath)) {
            Storage::delete($pdfFilePath);
        }
    }
}
