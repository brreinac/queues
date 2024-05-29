namespace App\Http\Controllers;

use App\Jobs\ProcessMicroservice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MicroserviceController extends Controller
{
    public function process(Request $request)
    {
        $data = $request->validate([
            'field1' => 'required|string',
            'field2' => 'required|integer',
            // Valida otros campos según sea necesario
        ]);

        // Despacha la job a la cola
        ProcessMicroservice::dispatch($data);

        return response()->json(['message' => 'Request is being processed'], 200);
    }

    public function processInBatch(Request $request)
    {
        $requests = $request->validate([
            'requests' => 'required|array',
            'requests.*.field1' => 'required|string',
            'requests.*.field2' => 'required|integer',
            // Valida otros campos según sea necesario
        ]);

        // Inicia un batch y añade trabajos al batch
        $batch = \Illuminate\Support\Facades\Bus::batch([])->dispatch();

        foreach ($requests['requests'] as $data) {
            $batch->add(new ProcessMicroservice($data));
        }

        return response()->json(['batch_id' => $batch->id], 200);
    }
}