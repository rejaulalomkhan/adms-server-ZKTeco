<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\Datatables;
use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\Attendance;
use App\Models\Office;
use Illuminate\Support\Facades\DB;

class DeviceController extends Controller
{
    // Menampilkan daftar device
    public function index(Request $request)
    {
        $data['lable'] = "Devices";
        $data['log'] = DB::table('devices')->select('id','no_sn','online')->orderBy('online', 'DESC')->get();
        return view('devices.index',$data);
    }

    public function DeviceLog(Request $request)
    {
        $data['lable'] = "Devices Log";
        $data['log'] = DB::table('device_log')->select('id','data','url')->orderBy('id','DESC')->get();
        
        return view('devices.log',$data);
    }
    
    public function FingerLog(Request $request)
    {
        $data['lable'] = "Finger Log";
        $data['log'] = DB::table('finger_log')->select('id','data','url')->orderBy('id','DESC')->get();
        return view('devices.log',$data);
    }

    public function Guide(Request $request)
    {
        $data['lable'] = "Device Setup Guide";
        return view('devices.guide', $data);
    }
    public function Attendance() {
       //$attendances = Attendance::latest('timestamp')->orderBy('id','DESC')->paginate(15);
       $attendances = DB::table('attendances')->select('id','sn','table','stamp','employee_id','timestamp','status1','status2','status3','status4','status5')->orderBy('id','DESC')->paginate(15);

        return view('devices.attendance', compact('attendances'));
        
    }

    // Menampilkan form tambah device
    public function create()
    {
        $offices = Office::orderBy('name')->get(['id','name']);
        return view('devices.create', compact('offices'));
    }

    // Menyimpan device baru ke database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'nullable|string|max:255',
            'no_sn' => 'required|string|max:255|unique:devices,no_sn',
            'lokasi' => 'nullable|string|max:255',
            'office_id' => 'nullable|exists:offices,id',
        ]);

        Device::create($validated);
        return redirect()->route('devices.index')->with('success', 'Device berhasil ditambahkan!');
    }

    // // Menampilkan detail device
    // public function show($id)
    // {
    //     $device = Device::find($id);
    //     return view('devices.show', compact('device'));
    // }

    // Menampilkan form edit device
    public function edit(Device $device)
    {
        $offices = Office::orderBy('name')->get(['id','name']);
        return view('devices.edit', compact('device','offices'));
    }

    // Mengupdate device ke database
    public function update(Request $request, Device $device)
    {
        $validated = $request->validate([
            'nama' => 'nullable|string|max:255',
            'no_sn' => 'required|string|max:255|unique:devices,no_sn,'.$device->id,
            'lokasi' => 'nullable|string|max:255',
            'office_id' => 'nullable|exists:offices,id',
        ]);

        $device->update($validated);
        return redirect()->route('devices.index')->with('success', 'Device berhasil diupdate!');
    }

    // Menghapus device dari database
    public function destroy(Device $device)
    {
        $device->delete();
        return redirect()->route('devices.index')->with('success', 'Device berhasil dihapus!');
    }
}
