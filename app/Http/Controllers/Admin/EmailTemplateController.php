<?php

namespace App\Http\Controllers\Admin;

use App\Scubaya\model\EmailTemplate;
use App\Scubaya\model\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class EmailTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function  merchantIndex(){

        $emailTemplate =   EmailTemplate::where('user_type','merchant')->get();

        return view('admin.manage.email_templates.merchant.index')
                ->with('merchantEmailTemplates',$emailTemplate);
    }

    public function  adminIndex(){

        $emailTemplate =   EmailTemplate::where('user_type','admin')->get();

        return view('admin.manage.email_templates.admin.index')
            ->with('adminEmailTemplates',$emailTemplate);
    }
    public function  userIndex(){

        $emailTemplate =   EmailTemplate::where('user_type','user')->get();

        return view('admin.manage.email_templates.user.index')
            ->with('userEmailTemplates',$emailTemplate);
    }

    protected function _prepare($request)
    {
        return $data   =   [
            'user_type'             =>  $request->user_type,
            'name'                  =>  $request->name,
            'action'                =>  $request->action,
            'subject'               =>  $request->subject,
            'sender_name'           =>  $request->sender_name,
            'sender_email'          =>  $request->sender_email,
            'template_content'      =>  $request->template_content,
        ];
    }

    public function addEmailTemplate(Request $request, $code)
    {
        if($request->isMethod('post')){
            $this->validate($request,[
                'name'              => 'required',
                'action'            => 'required|unique:email_templates',
                'subject'           => 'required',
                'sender_name'       => 'required',
                'sender_email'      => 'required',
                'template_content'  => 'required',
            ]);

            $data                   =   $this->_prepare($request);

            EmailTemplate::saveEmailTemplate($data);

            $request->session()->flash('success','Email Template Successfully Created');

            return redirect(route('scubaya::admin::manage::'.$code.'_email_template'));
        }

        return view('admin.manage.email_templates.'.$code.'.create');
    }

    public function editEmailTemplate(Request $request)
    {
        if($request->isMethod('post')){

            $this->validate($request,[
                'name'              => 'required',
                'action'            => 'required|unique:email_templates,action,'.$request->id.',id',
                'subject'           => 'required',
                'sender_name'       => 'required',
                'sender_email'      => 'required',
                'template_content'  => 'required',
            ]);

            $data   =   $this->_prepare($request);

            EmailTemplate::updateorCreate(['id' =>  $request->id], $data);

            $request->session()->flash('success','Email Template Updated Successfully');

            return redirect(route('scubaya::admin::manage::'.$request->user_type.'_email_template'));
        }

        $templateData =   EmailTemplate::where('id',$request->id)->first();

        return view('admin.manage.email_templates.'.$templateData->user_type.'.edit')->with('templateData',$templateData);
    }

    public function deleteEmailTemplate(Request $request)
    {
        EmailTemplate::destroy($request->id);

        $request->session()->flash('success','Template Deleted Successfully');

        return redirect()->back();
    }
}
