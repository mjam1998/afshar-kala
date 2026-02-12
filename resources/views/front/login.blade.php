<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('bootstrap/bootstrap.min.css')}}" >
    <link rel="icon" type="image/x-icon" href="{{asset('front/assets/logo-smal.png')}}">
    <link rel="icon" type="image/png" href="{{asset('front/assets/logo-smal.png')}}">

    <!-- برای دستگاه‌های اپل -->
    <link rel="apple-touch-icon" href="{{asset('front/assets/logo-smal.png')}}">
    <title>صفحه ورود</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Tahoma', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #e9ecef;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* ظرف اصلی */
        .container {
            background-color: white;
            display: flex;
            width: 800px;
            max-width: 95%;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        /* بخش تصویر */
        .image-section {
            flex: 1;
            background-image: url('https://via.placeholder.com/400x600'); /* آدرس عکس را اینجا تغییر دهید */
            background-size: cover;
            background-position: center;
            position: relative;
            min-height: 400px;
        }

        .image-overlay-text {
            position: absolute;
            bottom: 30px;
            left: 20px;
            color: white;
            font-size: 24px;
            font-weight: bold;
            text-shadow: 1px 1px 5px rgba(0,0,0,0.5);
        }

        /* بخش فرم */
        .form-section {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        h2 {
            margin-top: 0;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 1.5rem;
            margin-bottom: 30px;
        }

        .input-group {
            margin-bottom: 20px;
            position: relative;
        }

        .input-group input {
            width: 100%;
            padding: 10px 0;
            border: none;
            border-bottom: 1px solid #ccc;
            outline: none;
            font-size: 16px;
            transition: border-color 0.3s;
            text-align: right;
        }

        .input-group input:focus {
            border-bottom: 2px solid #333;
        }

        .btn-register {
            background-color: #333;
            color: white;
            border: none;
            padding: 12px 30px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            align-self: flex-start;
            transition: background 0.3s;
        }

        .btn-register:hover {
            background-color: #555;
        }

        /* ریسپانسیو برای موبایل */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                margin: 20px;
            }
            .image-section {
                min-height: 250px;
            }
            .form-section {
                padding: 30px;
            }
            .btn-register {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="image-section" style="background-image: url('{{asset('front/assets/login.jpg')}}');">

    </div>

    <div class="form-section">
        <h2>  ورود</h2>
        @if(session()->has('loginError'))
            <p class="alert alert-danger">{{session('loginError')}}</p>
        @endif
        <form method="post" action="{{route('login.post')}}" >

            @csrf
            <div class="input-group">
                <input type="tel" name="mobile" placeholder="شماره موبایل " required>
            </div>

            <div class="input-group">
                <input type="password" name="password" placeholder="رمز عبور" required>
            </div>



            <button type="submit" class="btn-register">تایید و ارسال ←</button>
        </form>
    </div>
</div>

</body>
</html>
