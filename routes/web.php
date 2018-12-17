<?php

Route::get('/', 'PrimeYearController@index')->name('prime-years.index');
Route::post('/', 'PrimeYearController@store')->name('prime-years.store');
