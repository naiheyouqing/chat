<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminCustomerRequest;
use App\Http\Requests\Admin\CompanyIdRequest;
use App\Http\Requests\Admin\CustomerIdRequest;
use App\Models\User;

class AdminCustomerController extends Controller
{
    public function getAllCustomersByCompanyId(CompanyIdRequest $request)
    {
        $id = $request->get('company_id');
        $data = User::where('type', $id)->paginate(8);
        $result = ['code' => '200', 'msg' => '成功', 'data' => $data];
        return response()->json($result);
    }

    public function getCustomerInfoByCustomerId(CustomerIdRequest $request)
    {
        $id = $request->get('customer_id');
        $data = User::where('id', $id)->first();
        if ($data) {
            $result = ['code' => '200', 'msg' => '成功', 'data' => [
                'customer_id' => $data->id,
                'customer_name' => $data->cname,
                'customer__phone' => $data->tel,
                'customer_create_time' => $data->created_at,
                'customer_state' => $data->status,
                'customer_commpany' => $data->type,
                'customer_other_info' => null
            ]];
        } else {
            $result = ['code' => '100', 'msg' => '失败', 'data' => null];
        }
        return response()->json($result);
    }
    public function addCustomer(AdminCustomerRequest $request)
    {
        $customer = new User();
        $customer->cname = $request->get('customer_name');
        $customer->password = bcrypt($request->get('customer_password'));
        $customer->type = $request->get('customer_company_id');
        $customer->tel = $request->get('customer_phone');
        $customer->created_at = date('Y-m-d h:i:s', time());
        $result = $customer->save();

        if ($result) {
            $result = ['code' => '200', 'msg' => '成功', 'data' => null];
        } else {
            $result = ['code' => '100', 'msg' => '失败', 'data' => null];
        }
        return response()->json($result);
    }
    public function deleteCustomer(CustomerIdRequest $request)
    {
        $id = $request->get('customer_id');
        $vis = User::where('id', $id)->delete();
        if ($vis) {
            $result = ['code' => '200', 'msg' => '成功', 'data' => null];
        } else {
            $result = ['code' => '100', 'msg' => '失败', 'data' => null];
        }
        return response()->json($result);
    }
}
