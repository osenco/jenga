Route::post('/generate-token','HooksController@generateToken');

Route::post('/account/balance','HooksController@checkAccountBalance');

Route::post('/account/mini/statement','HooksController@generateMiniStatement');

Route::post('/account/inquiry','HooksController@accountInquiry');

//Money movement
Route::post('/move/money/within','HooksController@moveMoneyWithinEquity');
Route::post('/move/mobile/money','HooksController@moveMoneyToMobile');
Route::post('/move/rtgs/money','HooksController@moveMoneyViaRtgs');
Route::post('/move/swift/money','HooksController@moveMoneyViaSwift');
Route::post('/move/eft/money','HooksController@moveMoneyViaEft');
Route::post('/move/pesalink/money/bank','HooksController@moveMoneyViaPesaLinkToBank');
Route::post('/move/pesalink/money/mobile','HooksController@moveMoneyViaPesaLinkToMobile');


//send money queries
Route::post('/money/inquire','HooksController@pesaLinkInqury');


//credit score
Route::post('/check/credit-score','HooksController@checkCreditScore');

//get forex rates
Route::post('/get/forex/rates','HooksController@getForexRates');
