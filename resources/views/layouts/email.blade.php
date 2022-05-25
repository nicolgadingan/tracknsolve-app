<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="x-apple-disable-message-reformatting">
    <style>
        :root{
            --cheese:       #EAD2AC;
            --forest:       #9CAFB7;
            --marine:       #4281A4;
            --marine-dark:  #326986;
            --forest-light: #bdcfd6;
            --rose:         #FE938C;
            --primary:      #0d6efd;
        }

        @media only screen and (min-width: 520px) {
            .container {
                width: 500px;
            }
        }

        @media (max-width: 520px) {
            .container {
                width: 100% !important;
                padding: 0px !important;
            }
        }

        body {
            font-family:        Calibri;
            color:              #4281A4;
            background-color:   #e7e7e7;
            margin:             0px;
            display:            flex;
            justify-content:    center;
        }

        .p-1    {   padding: 0.5rem;    }
        .p-2    {   padding: 1rem;      }
        .p-3    {   padding: 1.5rem;    }
        .p-4    {   padding: 2rem;      }

        .center {
            text-align: center;
        }

        .left {
            text-align: left;
        }

        .bg-white {
            background-color: white;
        }
        
        a.link-button {
            text-decoration: none;
            background-color: #0d6efd;
            color: white;
            padding: 0.5rem 1.5rem;
        }
    </style>
</head>
<body>
    <div class="container p-3">
        <div class="p-3">
            <img width="170px" src="{{ config('app.url', '') . '/imgs/yortik.svg' }}" alt="Yortik logo">
        </div>
        <div class="center bg-white p-3">
            @yield('content')
        </div>
        <div class="p-2 center">
            <small style="color: #889ca5;">
                This email is sent specifically to you and should not be shared with others.
            </small>
        </div>
    </div> 
</body>
</html>