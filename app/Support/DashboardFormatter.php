<?php

namespace App\Support;

use Carbon\Carbon;
use DateTimeInterface;

class DashboardFormatter
{
    public function money(float|int|string|null $value): string
    {
        return 'Rp ' . $this->number($value);
    }

    public function number(float|int|string|null $value): string
    {
        return number_format((float) $value, 0, ',', '.');
    }

    public function decimal(float|int|string|null $value, int $precision = 2): string
    {
        return number_format((float) $value, $precision, ',', '.');
    }

    public function unit(float|int|string|null $value, ?string $unit, int $precision = 2): string
    {
        $formattedValue = $this->decimal($value, $precision);
        $formattedValue = rtrim(rtrim($formattedValue, '0'), ',');

        return trim($formattedValue . ' ' . ($unit ?? ''));
    }

    public function date(DateTimeInterface|string|null $value): string
    {
        return $this->carbon($value)->translatedFormat('d F Y');
    }

    public function time(DateTimeInterface|string|null $value): string
    {
        return $this->carbon($value)->format('H:i') . ' WIB';
    }

    public function dateTime(DateTimeInterface|string|null $value): string
    {
        $date = $this->carbon($value);

        return $date->translatedFormat('d F Y') . ' ' . $date->format('H:i') . ' WIB';
    }

    private function carbon(DateTimeInterface|string|null $value): Carbon
    {
        return Carbon::parse($value ?? now())->locale('id');
    }
}
