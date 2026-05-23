<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    private $timezones = [
        'UTC'                 => 'UTC (0)',
        'Europe/Kaliningrad'  => 'Калининград (MSK-1)',
        'Europe/Moscow'       => 'Москва (MSK, +0)',
        'Europe/Samara'       => 'Самара (MSK+1)',
        'Asia/Yekaterinburg'  => 'Екатеринбург (MSK+2)',
        'Asia/Omsk'           => 'Омск (MSK+3)',
        'Asia/Krasnoyarsk'    => 'Красноярск (MSK+4)',
        'Asia/Irkutsk'        => 'Иркутск (MSK+5)',
        'Asia/Yakutsk'        => 'Якутск (MSK+6)',
        'Asia/Vladivostok'    => 'Владивосток (MSK+7)',
        'Asia/Magadan'        => 'Магадан (MSK+8)',
        'Asia/Kamchatka'      => 'Камчатка (MSK+9)',
    ];

    public function edit()
    {
        $user = Auth::user();
        $timezones = $this->timezones;
        return view('profile.edit', compact('user', 'timezones'));
    }

    // Обновление только имени, email, часового пояса
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'timezone' => 'required|in:' . implode(',', array_keys($this->timezones)),
        ]);

        $user->update($request->only('name', 'email', 'timezone'));

        return redirect()->route('dashboard')->with('success', 'Профиль обновлён');
    }

    // Отдельный метод для смены пароля
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('dashboard')->with('success', 'Пароль изменён');
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();

        // Выходим из системы
        Auth::logout();

        // Удаляем пользователя (каскадно удалятся клиенты, сеансы, фото и т.д.)
        $user->delete();

        // Очищаем сессию
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Ваш профиль удалён.');
    }
}