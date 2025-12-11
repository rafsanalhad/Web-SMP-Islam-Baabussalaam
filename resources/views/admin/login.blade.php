<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SMP Baabussalaam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        :root {--primary:#2a9d8f;--primary-dark:#21867a;--secondary:#264653;--accent:#e9c46a;--light:#f8f9fa;--dark:#343a40;}
        body{background:linear-gradient(135deg,var(--primary),var(--secondary));height:100vh;display:flex;align-items:center;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;overflow:hidden;position:relative}
        body::before{content:"";position:absolute;width:100%;height:100%;background-image:radial-gradient(circle at 90% 80%,rgba(233,196,106,0.15)0%,transparent 25%),radial-gradient(circle at 20% 30%,rgba(255,255,255,0.1)0%,transparent 25%);z-index:0}
        .login-container{position:relative;z-index:1}
        .login-card{background:rgba(255,255,255,0.95);border-radius:20px;box-shadow:0 15px 35px rgba(0,0,0,0.2);backdrop-filter:blur(10px);overflow:hidden;transition:transform 0.3s ease,box-shadow 0.3s ease}
        .login-card:hover{transform:translateY(-5px);box-shadow:0 20px 40px rgba(0,0,0,0.25)}
        .login-header{background:linear-gradient(135deg,var(--primary),var(--primary-dark));padding:2rem;text-align:center;color:white;position:relative}
        .logo-container{width:90px;height:90px;background:white;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 15px;box-shadow:0 5px 15px rgba(0,0,0,0.2)}
        .logo-container i{color:var(--primary);font-size:2.5rem}
        .login-body{padding:2rem}
        .form-control{border-radius:10px;padding:0.8rem 1rem;border:2px solid #e9ecef;transition:all 0.3s}
        .form-control:focus{border-color:var(--primary);box-shadow:0 0 0 0.25rem rgba(42,157,143,0.25)}
        .input-group-text{background:white;border-radius:10px 0 0 10px;border:2px solid #e9ecef;border-right:none}
        .btn-login{background:linear-gradient(135deg,var(--primary),var(--primary-dark));border:none;border-radius:10px;padding:0.8rem;font-weight:600;letter-spacing:0.5px;transition:all 0.3s;box-shadow:0 4px 10px rgba(42,157,143,0.4)}
        .btn-login:hover{transform:translateY(-2px);box-shadow:0 6px 15px rgba(42,157,143,0.5)}
        .floating-label{position:relative;margin-bottom:1.5rem}
        .floating-label label{position:absolute;top:50%;left:45px;transform:translateY(-50%);pointer-events:none;transition:0.3s ease all;color:#6c757d}
        .floating-label input:focus~label,.floating-label input:not(:placeholder-shown)~label{top:-10px;left:35px;font-size:0.8rem;background:white;padding:0 8px;color:var(--primary)}
        .wave-divider{position:absolute;bottom:0;left:0;width:100%;overflow:hidden;line-height:0;z-index:0}
        .wave-divider svg{position:relative;display:block;width:calc(100%+1.3px);height:70px}
        .wave-divider .shape-fill{fill:rgba(255,255,255,0.1)}
        @media(max-width:576px){.login-card{margin:0 15px}}
    </style>
</head>
<body>
    <div class="wave-divider"><svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path></svg></div>
    <div class="container login-container"><div class="row justify-content-center"><div class="col-md-5 col-lg-4"><div class="login-card"><div class="login-header"><div class="logo-container"><img src="{{ asset('assets/img/logo-smp.png') }}" alt="Logo SMP" class="img-fluid" style="height:60px;"></div><h4 class="fw-bold mb-1">Panel Admin</h4><p class="mb-0">SMP Islam Baabussalaam</p></div><div class="login-body"><form method="POST" action="{{ route('login') }}" id="loginForm">@csrf <div class="floating-label"><div class="input-group"><span class="input-group-text"><i class="fas fa-user"></i></span><input type="text" class="form-control" id="username" name="username" placeholder=" " required><label for="username">Username</label></div></div><div class="floating-label"><div class="input-group"><span class="input-group-text"><i class="fas fa-lock"></i></span><input type="password" class="form-control" id="password" name="password" placeholder=" " required><label for="password">Password</label></div></div><button type="submit" class="btn btn-login w-100 py-2 mt-3"><i class="fas fa-sign-in-alt me-2"></i>Login</button></form></div></div></div></div></div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>document.querySelectorAll('.form-control').forEach(input=>{input.addEventListener('focus',()=>{input.parentElement.parentElement.classList.add('focused')});input.addEventListener('blur',()=>{if(input.value===''){input.parentElement.parentElement.classList.remove('focused')}})});@php if($errors->any()):@endphp document.addEventListener('DOMContentLoaded',function(){Swal.fire({icon:'error',title:'Login Gagal',text:'{{$errors->first()}}',confirmButtonColor:'#2a9d8f',confirmButtonText:'Coba Lagi',backdrop:'rgba(0,0,0,0.4)',allowOutsideClick:false})});@php endif;@endphp document.getElementById('loginForm').addEventListener('submit',function(e){const btn=this.querySelector('button[type="submit"]');btn.innerHTML='<span class="spinner-border spinner-border-sm"></span> Memproses...';btn.disabled=true});</script>
</body>
</html>