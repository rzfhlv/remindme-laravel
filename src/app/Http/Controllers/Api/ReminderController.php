<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Reminder;
use App\Http\Resources\ReminderResource;
use App\Http\Resources\Reminder as ReminderCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class ReminderController extends Controller
{
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'remind_at' => 'required|int',
                'event_at' => 'required|int',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'ok' => false,
                    'err' => self::ERR_INVALID_REQUEST,
                    'msg' => [$validator->errors()],
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
    
            $reminder = Reminder::create([
                'title' => $request->title,
                'description' => $request->description,
                'remind_at' => $request->remind_at,
                'event_at' => $request->event_at,
                'created_by' => $request->user()->id,
            ]);
    
            return new ReminderResource($reminder);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'ok' => false,
                'err' => self::ERR_INTERNAL_SERVER,
                'msg' => self::MSG_INTERNAL_SERVER,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function all(Request $request)
    {
        try {
            $limit = $request->query('limit', 10);

            $reminders = Reminder::where('created_by', $request->user()->id)->orderBy('remind_at', 'desc')->take($limit)->get();
            $result = collect(["reminders" => $reminders, "limit" => $limit]);

            return new ReminderCollection($result);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'ok' => false,
                'err' => self::ERR_INTERNAL_SERVER,
                'msg' => self::MSG_INTERNAL_SERVER,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function get(Request $request, $id)
    {
        try {
            $reminder = Reminder::findOrFail($id);

            return new ReminderResource($reminder);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $err = self::ERR_INTERNAL_SERVER;
            $msg = self::MSG_INTERNAL_SERVER;
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;

            if ($th instanceof ModelNotFoundException) {
                $err = self::ERR_NOT_FOUND;
                $msg = self::MSG_NOT_FOUND;
                $code = Response::HTTP_NOT_FOUND;
            }

            return response()->json([
                'ok' => false,
                'err' => $err,
                'msg' => $msg,
            ], $code);
        }
        
    }

    public function update(Request $request, $id)
    {
        try {
            Reminder::findOrFail($id)->update($request->all());
            $reminder = Reminder::find($id);
            
            return new ReminderResource($reminder);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $err = self::ERR_INTERNAL_SERVER;
            $msg = self::MSG_INTERNAL_SERVER;
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;

            if ($th instanceof ModelNotFoundException) {
                $err = self::ERR_NOT_FOUND;
                $msg = self::MSG_NOT_FOUND;
                $code = Response::HTTP_NOT_FOUND;
            }

            return response()->json([
                'ok' => false,
                'err' => $err,
                'msg' => $msg,
            ], $code);
        }
        
    }

    public function delete(Request $request, $id)
    {
        try {
            Reminder::findOrFail($id)->delete();
            
            return response()->json([
                'ok' => true,
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $err = self::ERR_INTERNAL_SERVER;
            $msg = self::MSG_INTERNAL_SERVER;
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;

            if ($th instanceof ModelNotFoundException) {
                $err = self::ERR_NOT_FOUND;
                $msg = self::MSG_NOT_FOUND;
                $code = Response::HTTP_NOT_FOUND;
            }

            return response()->json([
                'ok' => false,
                'err' => $err,
                'msg' => $msg,
            ], $code);
        }
        
    }
}
