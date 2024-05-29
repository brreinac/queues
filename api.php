use App\Http\Controllers\MicroserviceController;

Route::post('/microservice/process', [MicroserviceController::class, 'process']);
Route::post('/microservice/process-batch', [MicroserviceController::class, 'processInBatch']);