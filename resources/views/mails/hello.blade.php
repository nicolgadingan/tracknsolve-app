<!DOCTYPE html>
<html>
<head>
    <title>yortik.com</title>
    <style>
    :root {
      --cheese:       #EAD2AC;
      --forest:       #9CAFB7;
      --marine:       #4281A4;
      --marine-dark:  #326986;
      --forest-light: #bdcfd6;
      --rose:         #FE938C;
    }
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: var(--marine);
            background-color: var(--forest-light);
        }
        main {
            padding: 1rem;
            display: flex;
            flex-direction: row;
            justify-content: center;
        }
        .container {
            border: 1px solid var(--forest);
            border-radius: 2rem;
            width: 450px;
            background-color: white;
            -webkit-box-shadow: 2px 3px 10px 2px rgba(28,28,28,0.35); 
          box-shadow: 2px 3px 10px 2px rgba(28,28,28,0.35);
        }
        .p-3 {
          padding: 1.5rem;
        }
        .center {
          text-align: center;
        }
        .right {
          text-align: right;
        }
        a {
          text-decoration: none;
        }
        .btn {
          border: 1px solid var(--forest);
          padding: 0.4rem 0.8rem; 
          border-radius: 0.5rem;
          background-color: var(--marine);
          color: white;
          font-size: large;
        }
        
        .btn:hover {
          background-color: var(--marine-dark);
        }
        hr {
          margin: 1.5rem 0rem;
        }
        .header {
          background-color: var(--cheese);
          border-top-left-radius: 2rem;
          border-top-right-radius: 2rem;
          padding-top: 1.5rem;
          padding-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <main>
        <div class="container">
            <div class="header">
                <div class="center">
                    <img height="60px" src="{{ asset('imgs/yortik.svg') }}" alt="Yortik logo"/>
                </div>
            </div>
            <div class="p-3">
                <div>
                    <h4>Greetings, {{ Str::ucfirst($mailData->first_name) }}!</h4>
                </div>
                <p>
                    Your colleague, <b>{{ ucwords(auth()->user()->first_name . ' ' . auth()->user()->last_name) }}</b>
                    added you to Yorik. <br>Use <b>{{ $mailData->username }}</b> as your username.
                </p>
                <p>
                    Follow below link to verify your access and start using Yorik.
                </p>
                <div class="center">
                    <a class="btn" href="{{ Request::root() }}/user/verify/{{ $mailData->emailVerify->token }}">
                        Verify Now
                    </a>
                </div>
                <br>
                <p>
                    <b>Having trouble with the button?</b>
                    <br>Copy and paste below link on your browser"
                </p>
                {{ Request::root() . "/user/verify/" . $mailData->emailVerify->token }}
                <p>
                    <b>Still having trouble?</b><br>Kindly reach out to your collegues / manager for assistance.
                </p>
                <br>
                <div>
                    Best Regards,<br>
                    <b>Yortik Team</b>
                </div>
            </div>
        </div>
    </main>
</body>
</html>