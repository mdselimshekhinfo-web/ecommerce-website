<?php

namespace App\Helpers;

class BanglaHelper
{
    public static function formatTaka($amount): string
    {
        return '৳' . number_format((float) $amount, 0, '.', ',');
    }

    public static function en2bn($number): string
    {
        $en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $bn = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        return str_replace($en, $bn, (string)$number);
    }

    public static function getDistricts(): array
    {
        return [
            'Dhaka' => ['name_bn' => 'ঢাকা', 'zone' => 'inside_dhaka', 'cost' => 60],
            'Gazipur' => ['name_bn' => 'গাজীপুর', 'zone' => 'inside_dhaka', 'cost' => 80],
            'Narayanganj' => ['name_bn' => 'নারায়ণগঞ্জ', 'zone' => 'inside_dhaka', 'cost' => 80],
            'Chattogram' => ['name_bn' => 'চট্টগ্রাম', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Sylhet' => ['name_bn' => 'সিলেট', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Rajshahi' => ['name_bn' => 'রাজশাহী', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Khulna' => ['name_bn' => 'খুলনা', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Barishal' => ['name_bn' => 'বরিশাল', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Rangpur' => ['name_bn' => 'রংপুর', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Mymensingh' => ['name_bn' => 'ময়মনসিংহ', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Cumilla' => ['name_bn' => 'কুমিল্লা', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Bogura' => ['name_bn' => 'বগুড়া', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Cox\'s Bazar' => ['name_bn' => 'কক্সবাজার', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Feni' => ['name_bn' => 'ফেনী', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Noakhali' => ['name_bn' => 'নোয়াখালী', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Brahmanbaria' => ['name_bn' => 'ব্রাহ্মণবাড়িয়া', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Tangail' => ['name_bn' => 'টাঙ্গাইল', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Faridpur' => ['name_bn' => 'ফরিদপুর', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Jessore' => ['name_bn' => 'যশোর', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Kushtia' => ['name_bn' => 'কুষ্টিয়া', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Pabna' => ['name_bn' => 'পাবনা', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Sirajganj' => ['name_bn' => 'সিরাজগঞ্জ', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Dinajpur' => ['name_bn' => 'দিনাজপুর', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Jamalpur' => ['name_bn' => 'জামালপুর', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Netrokona' => ['name_bn' => 'নেত্রকোনা', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Sherpur' => ['name_bn' => 'শেরপুর', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Sunamganj' => ['name_bn' => 'সুনামগঞ্জ', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Habiganj' => ['name_bn' => 'হবিগঞ্জ', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Moulvibazar' => ['name_bn' => 'মৌলভীবাজার', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Kishoreganj' => ['name_bn' => 'কিশোরগঞ্জ', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Manikganj' => ['name_bn' => 'মানিকগঞ্জ', 'zone' => 'inside_dhaka', 'cost' => 80],
            'Munshiganj' => ['name_bn' => 'মুন্সীগঞ্জ', 'zone' => 'inside_dhaka', 'cost' => 80],
            'Narsingdi' => ['name_bn' => 'নরসিংদী', 'zone' => 'inside_dhaka', 'cost' => 80],
            'Gopalganj' => ['name_bn' => 'গোপালগঞ্জ', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Madaripur' => ['name_bn' => 'মাদারীপুর', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Rajbari' => ['name_bn' => 'রাজবাড়ী', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Shariatpur' => ['name_bn' => 'শরীয়তপুর', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Bagerhat' => ['name_bn' => 'বাগেরহাট', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Chuadanga' => ['name_bn' => 'চুয়াডাঙ্গা', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Jhenaidah' => ['name_bn' => 'ঝিনাইদহ', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Magura' => ['name_bn' => 'মাগুরা', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Meherpur' => ['name_bn' => 'মেহেরপুর', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Narail' => ['name_bn' => 'নড়াইল', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Satkhira' => ['name_bn' => 'সাতক্ষীরা', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Barguna' => ['name_bn' => 'বরগুনা', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Bhola' => ['name_bn' => 'ভোলা', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Jhalokati' => ['name_bn' => 'ঝালকাঠি', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Patuakhali' => ['name_bn' => 'পটুয়াখালী', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Pirojpur' => ['name_bn' => 'পিরোজপুর', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Bandarban' => ['name_bn' => 'বান্দরবান', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Chandpur' => ['name_bn' => 'চাঁদপুর', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Khagrachhari' => ['name_bn' => 'খাগড়াছড়ি', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Lakshmipur' => ['name_bn' => 'লক্ষ্মীপুর', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Rangamati' => ['name_bn' => 'রাঙ্গামাটি', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Joypurhat' => ['name_bn' => 'জয়পুরহাট', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Naogaon' => ['name_bn' => 'নওগাঁ', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Natore' => ['name_bn' => 'নাটোর', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Chapainawabganj' => ['name_bn' => 'চাঁপাইনবাবগঞ্জ', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Gaibandha' => ['name_bn' => 'গাইবান্ধা', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Kurigram' => ['name_bn' => 'কুড়িগ্রাম', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Lalmonirhat' => ['name_bn' => 'লালমনিরহাট', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Nilphamari' => ['name_bn' => 'নীলফামারী', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Panchagarh' => ['name_bn' => 'পঞ্চগড়', 'zone' => 'outside_dhaka', 'cost' => 120],
            'Thakurgaon' => ['name_bn' => 'ঠাকুরগাঁও', 'zone' => 'outside_dhaka', 'cost' => 120],
        ];
    }
}
