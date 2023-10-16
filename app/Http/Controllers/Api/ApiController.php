<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AndroidAppCrashes;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    protected function athlete(int $id): JsonResponse
    {
        $user = auth('sanctum')->user();

        if (empty($user)) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 401);
        }

        $athlete = User::findOrFail($id, ['id', 'nickname', 'photo', 'first_name', 'last_name', 'created_at']);

        if (empty($athlete)) {
            return response()->json([
                'success' => false,
                'message' => 'Athlete not found'
            ], 404);
        }

        return response()->json(['athlete' => $athlete]);
    }

    protected function errorReporting(Request $request): JsonResponse
    {
        $data = $request->all();
        $issueId = $this->calculateIssueId($data['STACK_TRACE'], $data['PACKAGE_NAME']);

        if(AndroidAppCrashes::where('issue_id', $issueId)->exists()){
            exit();
        }

        AndroidAppCrashes::create([
            'user_id' => auth('sanctum')->id(),
            'issue_id' => $issueId,
            'report_id' => $data['REPORT_ID'],
            'app_version_code' => $data['APP_VERSION_CODE'],
            'app_version_name' => $data['APP_VERSION_NAME'],
            'package_name' => $data['PACKAGE_NAME'],
            'file_path' => $data['FILE_PATH'],
            'phone_model' => $data['PHONE_MODEL'],
            'android_version' => $data['ANDROID_VERSION'],
            'build' => json_encode($data['BUILD']),
            'brand' => $data['BRAND'],
            'product' => $data['PRODUCT'],
            'total_mem_size' => $data['TOTAL_MEM_SIZE'],
            'available_mem_size' => $data['AVAILABLE_MEM_SIZE'],
            'custom_data' => json_encode($data['CUSTOM_DATA']),
            'stack_trace' => $data['STACK_TRACE'],
            'initial_configuration' => json_encode($data['INITIAL_CONFIGURATION']),
            'crash_configuration' => json_encode($data['CRASH_CONFIGURATION']),
            'display' => json_encode($data['DISPLAY']),
            'user_comment' => $data['USER_COMMENT'],
            'user_app_start_date' => date('Y-m-d H:i:s', strtotime($data['USER_APP_START_DATE'])),
            'user_crash_date' => date('Y-m-d H:i:s', strtotime($data['USER_CRASH_DATE'])),
            'dumpsys_meminfo' => $data['DUMPSYS_MEMINFO'],
            'logcat' => $data['LOGCAT'],
            'installation_id' => $data['INSTALLATION_ID'],
            'device_features' => json_encode($data['DEVICE_FEATURES']),
            'environment' => json_encode($data['ENVIRONMENT']),
            'shared_preferences' => json_encode($data['SHARED_PREFERENCES']),
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    private function calculateIssueId(string $stackTrace, string $packageName): string {
        return md5($this->shortStackTrace($stackTrace, $packageName));
    }

    private function shortStackTrace(string $stackTrace, string $packageName): string {
        $lines = explode("\n", $stackTrace);
        if (in_array(": ", $lines) === FALSE && in_array($packageName, $lines) === FALSE) {
            $value = $lines[0];
        } else {
            $value = "";
            foreach ($lines as $line) {
                if (str_contains($line, ": ") || str_contains($line, $packageName)
                    || str_contains($line, "Error") || str_contains($line, "Exception")) {
                    $value .= $line . "<br />";
                }
            }
        }
        return $value;
    }
}