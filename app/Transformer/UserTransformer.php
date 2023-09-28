<?php

namespace App\Transformer;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(User $user)
    {

        return [
            'id' => $user->id,
            'first_name' => $user->first_name ?? '',
            'last_name' => $user->last_name ?? '',
            'username' => $user->username ?? '',
            'email' => $user->email ?? '',
            'password' => $user->password ?? '',
            'phone_number' => $user->phone_number ?? '',
            'profile_image' => $user->profile_image ?? '',
            'status' => $user->status ?? '',
            'email_verified_at' => $user->email_verified_at ?? '',
            "deleted_at" => $user->deleted_at ?? '',
            "created_at" => $user->created_at ?? '',
            "updated_at" => $user->updated_at ?? '',
                "user_billing_address" => [
                    'user_id' => $user->userBillingAddress->user_id ?? '',
                    'id' => $user->userBillingAddress->id ?? '',
                    'address' => $user->userBillingAddress->address ?? '',
                    'address_2' => $user->userBillingAddress->address_2 ?? '',
                    'city' => $user->userBillingAddress->city ?? '',
                    'state' => $user->userBillingAddress->state ?? '',
                    'zip_code' => $user->userBillingAddress->zip_code ?? '',
                    'created_at' => $user->userBillingAddress->created_at ?? '',
                    'updated_at' => $user->userBillingAddress->updated_at ?? '',
                ],
        ];
    }

    
}
