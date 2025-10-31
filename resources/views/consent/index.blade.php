@extends('dashboard.auth.layout.auth_layout')

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
        /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}a{background-color:transparent}[hidden]{display:none}html{font-family:system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;line-height:1.5}*,:after,:before{box-sizing:border-box;border:0 solid #e2e8f0}a{color:inherit;text-decoration:inherit}svg,video{display:block;vertical-align:middle}video{max-width:100%;height:auto}.bg-white{--bg-opacity:1;background-color:#fff;background-color:rgba(255,255,255,var(--bg-opacity))}.bg-gray-100{--bg-opacity:1;background-color:#f7fafc;background-color:rgba(247,250,252,var(--bg-opacity))}.border-gray-200{--border-opacity:1;border-color:#edf2f7;border-color:rgba(237,242,247,var(--border-opacity))}.border-t{border-top-width:1px}.flex{display:flex}.grid{display:grid}.hidden{display:none}.items-center{align-items:center}.justify-center{justify-content:center}.font-semibold{font-weight:600}.h-5{height:1.25rem}.h-8{height:2rem}.h-16{height:4rem}.text-sm{font-size:.875rem}.text-lg{font-size:1.125rem}.leading-7{line-height:1.75rem}.mx-auto{margin-left:auto;margin-right:auto}.ml-1{margin-left:.25rem}.mt-2{margin-top:.5rem}.mr-2{margin-right:.5rem}.ml-2{margin-left:.5rem}.mt-4{margin-top:1rem}.ml-4{margin-left:1rem}.mt-8{margin-top:2rem}.ml-12{margin-left:3rem}.-mt-px{margin-top:-1px}.max-w-6xl{max-width:72rem}.min-h-screen{min-height:100vh}.overflow-hidden{overflow:hidden}.p-6{padding:1.5rem}.py-4{padding-top:1rem;padding-bottom:1rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.pt-8{padding-top:2rem}.fixed{position:fixed}.relative{position:relative}.top-0{top:0}.right-0{right:0}.shadow{box-shadow:0 1px 3px 0 rgba(0,0,0,.1),0 1px 2px 0 rgba(0,0,0,.06)}.text-center{text-align:center}.text-gray-200{--text-opacity:1;color:#edf2f7;color:rgba(237,242,247,var(--text-opacity))}.text-gray-300{--text-opacity:1;color:#e2e8f0;color:rgba(226,232,240,var(--text-opacity))}.text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.text-gray-500{--text-opacity:1;color:#a0aec0;color:rgba(160,174,192,var(--text-opacity))}.text-gray-600{--text-opacity:1;color:#718096;color:rgba(113,128,150,var(--text-opacity))}.text-gray-700{--text-opacity:1;color:#4a5568;color:rgba(74,85,104,var(--text-opacity))}.text-gray-900{--text-opacity:1;color:#1a202c;color:rgba(26,32,44,var(--text-opacity))}.underline{text-decoration:underline}.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.w-5{width:1.25rem}.w-8{width:2rem}.w-auto{width:auto}.grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}@media (min-width:640px){.sm\:rounded-lg{border-radius:.5rem}.sm\:block{display:block}.sm\:items-center{align-items:center}.sm\:justify-start{justify-content:flex-start}.sm\:justify-between{justify-content:space-between}.sm\:h-20{height:5rem}.sm\:ml-0{margin-left:0}.sm\:px-6{padding-left:1.5rem;padding-right:1.5rem}.sm\:pt-0{padding-top:0}.sm\:text-left{text-align:left}.sm\:text-right{text-align:right}}@media (min-width:768px){.md\:border-t-0{border-top-width:0}.md\:border-l{border-left-width:1px}.md\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (min-width:1024px){.lg\:px-8{padding-left:2rem;padding-right:2rem}}@media (prefers-color-scheme:dark){.dark\:bg-gray-800{--bg-opacity:1;background-color:#2d3748;background-color:rgba(45,55,72,var(--bg-opacity))}.dark\:bg-gray-900{--bg-opacity:1;background-color:#1a202c;background-color:rgba(26,32,44,var(--bg-opacity))}.dark\:border-gray-700{--border-opacity:1;border-color:#4a5568;border-color:rgba(74,85,104,var(--border-opacity))}.dark\:text-white{--text-opacity:1;color:#fff;color:rgba(255,255,255,var(--text-opacity))}.dark\:text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.dark\:text-gray-500{--tw-text-opacity:1;color:#6b7280;color:rgba(107,114,128,var(--tw-text-opacity))}}
    </style>

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>

    <div class="relative flex items-top justify-center min-h-screen sm:items-center py-4 sm:pt-0">
        @if (Route::has('login'))
            <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                @auth
                    <li class="nav-item dropdown" style="list-style-type: none;">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ Config::get('languages')[App::getLocale()] }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            @foreach (Config::get('languages') as $lang => $language)
                                @if ($lang != App::getLocale())
                                    <a class="dropdown-item" href="{{ route('lang.switch', $lang) }}"> {{$language}}</a>
                                @endif
                            @endforeach
                        </div>
                    </li>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</a>

                @endauth
            </div>
        @endif

        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{$message}}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
                <img width="170" src="{{asset('images/logo.png')}}" alt="logo">
            </div>

            <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <div class="p-6 pb-0">
                        <div class="ml-12 flex items-center ">
                            <img src="{{asset('images/logo2.png')}}" height="50" width="150">
                        </div>
                    </div>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-1 pt-3">
                    <div class="p-6">
                        <div class="ml-12 mr-5">
                            @if(Config::get('languages')[App::getLocale()] === "English")
                                <div class=" text-gray-600 dark:text-gray-400 text-sm">
                                    <div class=" text-lg  font-semibold text-gray-900 dark:text-white mb-2  " style="text-align: center; font-size:24pt;">e Consent form</div>
                                    <div class=" text-lg  font-semibold text-gray-900 dark:text-white">Program name:{{$program->name ?? ''}}</div>
                                    <div class=" text-lg  font-semibold text-gray-900 dark:text-white">Sponsor:{{$client->client_name ?? ''}}</div>
                                    <div class=" text-lg  font-semibold text-gray-900 dark:text-white">Treating Physician Name:{{$physician->user->first_name ?? ''}}</div>
                                </div>
                            @else
                                <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm" style="text-align: right">
                                    <div class=" text-lg  font-semibold text-gray-900 dark:text-white mb-2  " style="text-align: center; font-size:24pt;">نموذج موافقة</div>
                                    <div class=" text-lg  font-semibold text-gray-900 dark:text-white">اسم البرنامج: {{$program->name ?? ''}}</div>
                                    <div class=" text-lg  font-semibold text-gray-900 dark:text-white">الجهة الراعية: {{$client->client_name ?? ''}}</div>
                                    <div class=" text-lg  font-semibold text-gray-900 dark:text-white">اسم الطبيب المعالج: {{$physician->user->first_name ?? ''}}</div>
                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="p-6 pt-0">
                        <div class="ml-12 mr-5">
                            @if(Config::get('languages')[App::getLocale()] === "English")
                                <div class=" text-gray-600 dark:text-gray-400 text-sm">

                                    I, the undersigned<br>
                                    confirm that my treating physician has provided me with all the required information about this program to enable me to sign this consent.
                                    I agree to participate in this patient support program at my own free will and I consent to share my medical information with ClinServ (a third party delegated by the sponsor) for eligibility assessment.
                                    <br>
                                    I understand that my authorization will not expire, but I can revoke my authorization by notifying my treating physician and ClinServ.
                                    <br>
                                    Sponsor’s {{$client->client_name ?? ''}} and/ or ClinServ may, without liability and at its sole discretion, decide to stop my participation in the program or terminate the program at any time and I will be notified accordingly.
                                    <br>
                                    This authorizes ClinServ to store and process my medical information which is related to this program. This personal data will be secured against unauthorized access and will be kept confidential.
                                    <br>
                                    I understand and agree that in case of adverse events, collected data may also be provided to the program sponsor and/or official databases for reporting of adverse events and/or other safety relevant information.

                                </div>
                            @else
                                <div class=" text-gray-600 dark:text-gray-400 text-sm" >
                                    <div style="text-align: right;"><p class="MsoNormal" dir="RTL" style="direction: rtl; unicode-bidi: embed;"><span lang="AR-SA" style="font-size: 12pt; font-family: WinSoftPro-Medium; color: rgb(34, 34, 33);">انا
الموقع ادناه&nbsp;</span></p><span style="font-size: 12pt;">

</span><p class="MsoNormal" dir="RTL" style="direction: rtl; unicode-bidi: embed;"><span lang="AR-SA" style="font-size:10.0pt;font-family:WinSoftPro-Medium;mso-ascii-font-family:
WinSoftPro-Medium;mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;
mso-hansi-font-family:Calibri;mso-hansi-theme-font:minor-latin;color:#222221">أؤكّد
بأن طبيبي المعالج قد زوّدني بجميع المعلومات المطلوبة بشأن هذا البرنامج بما يمكنني
من التوقيع على هذا النموذج.</span><span lang="AR-SY" style="font-size:10.0pt;
font-family:WinSoftPro-Medium;mso-ascii-font-family:WinSoftPro-Medium;
mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;mso-hansi-font-family:
Calibri;mso-hansi-theme-font:minor-latin;color:#222221;mso-bidi-language:AR-SY"><o:p></o:p></span></p><span style="font-size: 12pt;">

</span><p class="MsoNormal" dir="RTL" style="direction: rtl; unicode-bidi: embed;"><span lang="AR-SA" style="font-size:10.0pt;font-family:WinSoftPro-Medium;mso-ascii-font-family:
WinSoftPro-Medium;mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;
mso-hansi-font-family:Calibri;mso-hansi-theme-font:minor-latin;color:#222221">انني
اوافق على المشاركة في برنامج دعم المرضى هذا بملء ارادتي واقبل بإطلاع شركة </span><span dir="LTR" style="color:#3B3B3A;mso-font-width:85%">ClinServ</span><span dir="RTL"></span><span dir="RTL"></span><span style="font-size:10.0pt;font-family:WinSoftPro-Medium;
mso-ascii-font-family:Calibri;mso-ascii-theme-font:minor-latin;mso-fareast-font-family:
Calibri;mso-fareast-theme-font:minor-latin;mso-hansi-font-family:Calibri;
mso-hansi-theme-font:minor-latin;color:#222221"><span dir="RTL"></span><span dir="RTL"></span> <span lang="AR-SA">(</span></span><span lang="AR-SA" style="font-size:10.0pt;font-family:WinSoftPro-Medium;mso-ascii-font-family:
WinSoftPro-Medium;mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;
mso-hansi-font-family:Calibri;mso-hansi-theme-font:minor-latin;color:#222221">فريق
ثالث منتدب من</span><span dir="LTR"></span><span dir="LTR"></span><span lang="AR-SA" dir="LTR" style="font-size:10.0pt;font-family:WinSoftPro-Medium;mso-ascii-font-family:
WinSoftPro-Medium;mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;
mso-hansi-font-family:Calibri;mso-hansi-theme-font:minor-latin;color:#222221"><span dir="LTR"></span><span dir="LTR"></span> </span><span lang="AR-SA" style="font-size:
10.0pt;font-family:WinSoftPro-Medium;mso-ascii-font-family:WinSoftPro-Medium;
mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;mso-hansi-font-family:
Calibri;mso-hansi-theme-font:minor-latin;color:#222221">قبل الجهة الراعية) على معلوماتي
الطبية من أجل تقييم الاهلية</span><span dir="LTR"></span><span dir="LTR"></span><span dir="LTR" style="font-size:10.0pt;font-family:WinSoftPro-Medium;mso-ascii-font-family:
WinSoftPro-Medium;mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;
mso-hansi-font-family:Calibri;mso-hansi-theme-font:minor-latin;color:#222221"><span dir="LTR"></span><span dir="LTR"></span>.<o:p></o:p></span></p><span style="font-size: 12pt;">

</span><p class="MsoNormal" dir="RTL" style="direction: rtl; unicode-bidi: embed;"><span lang="AR-SA" style="font-size:10.0pt;font-family:WinSoftPro-Medium;mso-ascii-font-family:
WinSoftPro-Medium;mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;
mso-hansi-font-family:Calibri;mso-hansi-theme-font:minor-latin;color:#222221">انني
ادرك ان صلاحية هذا الإذن لن تنتهي وانني استطيع ان ألغي هذا الإذن عن طريق إخبار طبيبي
المعالج وشركة&nbsp;</span><span dir="LTR" style="font-size: 1rem; color: rgb(59, 59, 58);">ClinServ</span><span dir="RTL" style="font-size: 1rem;"></span><span dir="RTL" style="font-size: 1rem;"></span><span lang="AR-SA" style="font-size: 10pt; font-family: WinSoftPro-Medium; color: rgb(34, 34, 33);"><span dir="RTL"></span><span dir="RTL"></span>.</span></p><span style="font-size: 12pt;">

</span><p class="MsoNormal" dir="RTL" style="direction: rtl; unicode-bidi: embed;"><span lang="AR-SA" style="font-size:10.0pt;font-family:WinSoftPro-Medium;mso-ascii-font-family:
WinSoftPro-Medium;mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;
mso-hansi-font-family:Calibri;mso-hansi-theme-font:minor-latin;color:#222221">يحق
لـ&nbsp;&nbsp;</span><span dir="LTR" style="font-size:10.0pt;font-family:WinSoftPro-Medium;
mso-ascii-font-family:WinSoftPro-Medium;mso-fareast-font-family:Calibri;
mso-fareast-theme-font:minor-latin;mso-hansi-font-family:Calibri;mso-hansi-theme-font:
minor-latin;color:#222221;background:yellow;mso-highlight:yellow"></span><span dir="LTR" style="font-size:10.0pt;font-family:&quot;Calibri&quot;,sans-serif;mso-ascii-font-family:
WinSoftPro-Medium;mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;
mso-hansi-theme-font:minor-latin;mso-bidi-font-family:WinSoftPro-Medium;
color:#222221;background:yellow;mso-highlight:yellow">’</span><span dir="LTR" style="font-size:10.0pt;font-family:WinSoftPro-Medium;mso-ascii-font-family:
WinSoftPro-Medium;mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;
mso-hansi-font-family:Calibri;mso-hansi-theme-font:minor-latin;color:#222221;
background:yellow;mso-highlight:yellow">&nbsp;{{$client->client_name ?? ''}}</span><span dir="RTL"></span><span dir="RTL"></span><span lang="AR-SA" style="font-size:10.0pt;font-family:WinSoftPro-Medium;
mso-ascii-font-family:WinSoftPro-Medium;mso-fareast-font-family:Calibri;
mso-fareast-theme-font:minor-latin;mso-hansi-font-family:Calibri;mso-hansi-theme-font:
minor-latin;color:#222221"><span dir="RTL"></span><span dir="RTL"></span> و/او </span><span dir="LTR" style="font-size:10.0pt;font-family:WinSoftPro-Medium;mso-ascii-font-family:
WinSoftPro-Medium;mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;
mso-hansi-font-family:Calibri;mso-hansi-theme-font:minor-latin;color:#222221">ClinServ</span><span dir="RTL"></span><span dir="RTL"></span><span lang="AR-SA" style="font-size:10.0pt;
font-family:WinSoftPro-Medium;mso-ascii-font-family:WinSoftPro-Medium;
mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;mso-hansi-font-family:
Calibri;mso-hansi-theme-font:minor-latin;color:#222221"><span dir="RTL"></span><span dir="RTL"></span>، من دون تحمّل أي مسؤولية ووفقاً لاستنسابها الخاص، ان تقرّر وقف
مشاركتي في البرنامج او انهاء البرنامج في اي وقت، وبأنه سوف يتمّ إعلامي بذلك</span><span dir="LTR"></span><span dir="LTR"></span><span dir="LTR" style="font-size:10.0pt;
font-family:WinSoftPro-Medium;mso-ascii-font-family:WinSoftPro-Medium;
mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;mso-hansi-font-family:
Calibri;mso-hansi-theme-font:minor-latin;color:#222221"><span dir="LTR"></span><span dir="LTR"></span>.<o:p></o:p></span></p><span style="font-size: 12pt;">

</span><p class="MsoNormal" dir="RTL" style="direction: rtl; unicode-bidi: embed;"><span lang="AR-SA" style="font-size:10.0pt;font-family:WinSoftPro-Medium;mso-ascii-font-family:
WinSoftPro-Medium;mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;
mso-hansi-font-family:Calibri;mso-hansi-theme-font:minor-latin;color:#222221">هذا
التوقيع يسمح لشركة </span><span dir="LTR" style="color:#3B3B3A;mso-font-width:
85%">ClinServ</span><span dir="RTL"></span><span dir="RTL"></span><span style="font-size:10.0pt;font-family:WinSoftPro-Medium;mso-ascii-font-family:
WinSoftPro-Medium;mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;
mso-hansi-font-family:Calibri;mso-hansi-theme-font:minor-latin;color:#222221"><span dir="RTL"></span><span dir="RTL"></span> <span lang="AR-SA">بأن تخزّن وتعالج معلوماتي
الطبية المرتبطة بهذا البرنامج. وسوف تتم حماية&nbsp;</span></span><span lang="AR-SA" style="font-size: 10pt; font-family: WinSoftPro-Medium; color: rgb(34, 34, 33);">هذه
البيانات الشخصية ضد اي اطلاع غير مرخص كما سيتم الحفاظ على سريّتها</span><span dir="LTR" style="font-size: 1rem;"></span><span dir="LTR" style="font-size: 1rem;"></span><span dir="LTR" style="font-size: 10pt; font-family: WinSoftPro-Medium; color: rgb(34, 34, 33);"><span dir="LTR"></span><span dir="LTR"></span>.</span></p><span style="font-size: 12pt;">

</span><p class="MsoNormal" dir="RTL" style="direction: rtl; unicode-bidi: embed;"><span lang="AR-SA" style="font-size:10.0pt;font-family:WinSoftPro-Medium;mso-ascii-font-family:
WinSoftPro-Medium;mso-fareast-font-family:Calibri;mso-fareast-theme-font:minor-latin;
mso-hansi-font-family:Calibri;mso-hansi-theme-font:minor-latin;color:#222221">انني
افهم وأقرّ بأنه، في حال ظهور اي آثار جانبية، يمكن كذلك تزويد راعي البرنامج و/او
قواعد البيانات الرسمية،</span><span dir="LTR" style="font-size:10.0pt;font-family:
WinSoftPro-Medium;mso-ascii-font-family:WinSoftPro-Medium;mso-fareast-font-family:
Calibri;mso-fareast-theme-font:minor-latin;mso-hansi-font-family:Calibri;
mso-hansi-theme-font:minor-latin;color:#222221"><o:p></o:p></span><span style="color: rgb(32, 33, 36); font-family: consolas, &quot;lucida console&quot;, &quot;courier new&quot;, monospace; font-size: 12px; text-align: left; white-space: pre-wrap;">بالبيانات التي تمّ جمعها من اجل الافادة عن الآثار الجانبية</span></p><span style="color: rgb(32, 33, 36); font-family: consolas, &quot;lucida console&quot;, &quot;courier new&quot;, monospace; font-size: 12px; text-align: left; white-space: pre-wrap;">و/او اي معلومات اخرى تتعلق بالسلامة</span><span style="font-size: 12pt;">

</span><span lang="AR-SA" dir="RTL" style="font-size: 12pt; font-family: WinSoftPro-Medium; color: rgb(34, 34, 33);"></span><br></div>
                                </div>
                            @endif

                        </div>
                    </div>

                    <div class="p-6 pt-0">
                        <div class="ml-12 mr-5">
                            <form action="{{ route('consent.store') }}" method="post"
                            @if(Config::get('languages')[App::getLocale()] === "English")

                            @else
                                style="direction: rtl;"
                            @endif
                            >
                                {{ csrf_field() }}
                                <input id="client_id" type="hidden"  name="client_id" value="{{ $client->id }}"  >
                                <input id="program_id" type="hidden"  name="program_id" value="{{ $program->id }}">
                                <input id="physician_id" type="hidden"  name="physician_id" value="{{ $physician->id }}">
                                <input id="patient_id" type="hidden"  name="patient_id" value="{{ $patient->id }}" >

                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="input-group mb-3">
                                            <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name" autofocus placeholder="{{ __('labels.first_name') }}">
                                            @error('first_name')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-md-5">
                                        <div class="input-group mb-3">
                                            <input id="family_name" type="text" class="form-control @error('family_name') is-invalid @enderror" name="family_name" value="{{ old('family_name') }}" required autocomplete="family_name" autofocus placeholder="{{ __('labels.family_name') }}">
                                            @error('family_name')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2">
                                        <button type="submit" class="btn btn-primary btn-block">{{ __('labels.SignIn') }}</button>
                                    </div>

                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-center mt-4 sm:items-center sm:justify-between">
                <div class="text-center text-sm text-gray-500 sm:text-left">
                    <div class="flex items-center">

                    </div>
                </div>

                <div class="ml-4 text-center text-sm text-gray-500 sm:text-right sm:ml-0">

                </div>
            </div>
        </div>
    </div>

@endsection
