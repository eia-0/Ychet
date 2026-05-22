<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // Список доступных часовых поясов (русские названия)
    private $timezones = [
        'UTC'               => 'UTC (0)',
        'Europe/Kaliningrad' => 'Калининград (MSK-1)',
        'Europe/Moscow'      => 'Москва (MSK, +0)',
        'Europe/Samara'      => 'Самара (MSK+1)',
        'Asia/Yekaterinburg' => 'Екатеринбург (MSK+2)',
        'Asia/Omsk'          => 'Омск (MSK+3)',
        'Asia/Krasnoyarsk'   => 'Красноярск (MSK+4)',
        'Asia/Irkutsk'       => 'Иркутск (MSK+5)',
        'Asia/Yakutsk'       => 'Якутск (MSK+6)',
        'Asia/Vladivostok'   => 'Владивосток (MSK+7)',
        'Asia/Magadan'       => 'Магадан (MSK+8)',
        'Asia/Kamchatka'     => 'Камчатка (MSK+9)',
    ];

    public function edit()
    {
        $user = Auth::user();
        $timezones = $this->timezones;
        return view('profile.edit', compact('user', 'timezones'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . Auth::id(),
            'timezone' => 'required|in:' . implode(',', array_keys($this->timezones)),
        ]);

        Auth::user()->update($request->only('name', 'email', 'timezone'));

        return redirect()->route('dashboard')->with('success', 'Профиль обновлён');
    }
}