<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingsFs extends Model
{

    protected $fillable = [
        'site_name'.'card_percent', 'coin_percent','bitcoin','ethereum','lite','ripple','site_title', 'company_name', 'contact_email', 'contact_number','app_contact','address',
        'ptc', 'ppv', 'payment_proof', 'latest_deposit','minimum_deposit','minimum_withdraw','transfer',
        'self_transfer','other_transfer','live_chat','chat_code','signup_bonus','link_share','referral_signup',
        'referral_deposit','referral_advert','referral_upgrade','status','membership_upgrade','invest','aff_share','buy_traffic',
        'minimum_transfer','login','daily_rewards',
    ];

}
