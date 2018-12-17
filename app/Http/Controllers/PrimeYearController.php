<?php

namespace App\Http\Controllers;

use App\PrimeYear;
use Illuminate\Http\Request;

class PrimeYearController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $prime_years = PrimeYear::orderBy('id', 'desc')->get();
        }

        return view('home');
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'year' => 'required|numeric|min:113|unique:prime_years,year'
        ]);

        $prime_years = $this->getPrimeYears($request->year);

        if (! $christmas_day = $this->getChristmasDay(end($prime_years))) {
            return null;
        }

        PrimeYear::create([
            'year' => $request->year,
            'day' => $christmas_day,
        ]);

        return redirect()->route('prime-years.index');
    }

    /**
     * Get the day for given year.
     * @param  integer $year
     * @return date
     */
    protected function getChristmasDay($year)
    {
        if ($year) {
            return date('l', strtotime("{$year}-12-25"));
        }
    }

    /**
     * Get the 30 prime years from the given year.
     * @param  integer $year
     * @return array
     */
    protected function getPrimeYears($year)
    {
        $prime_years = [];

        for ($year; count($prime_years) < 30; $year--) {
            if ($this->isPrime($year)) {
                $prime_years[] = $year;
            }
        }

        return $prime_years;
    }

    /**
     * Determine wether a number is prime or not.
     * @param  integer  $number
     * @return boolean
     */
    protected function isPrime($number)
    {
        if ($number < 2) {
            return false;
        }

        for ($i = 2; $i <= $number / 2; $i++) {
            if ($number % $i == 0) {
                return false;
            }
        }

        return true;
    }
}
