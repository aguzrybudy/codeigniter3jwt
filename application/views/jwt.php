<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Authorization using JSON Web Tokens and PHP</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
  <style>
  html,
body {
  height: 100%;
}

body {
  display: flex;
  align-items: center;
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #f5f5f5;
}

.form-signin {
  width: 100%;
  max-width: 330px;
  padding: 15px;
  margin: auto;
}

.form-signin .checkbox {
  font-weight: 400;
}

.form-signin .form-control {
  position: relative;
  box-sizing: border-box;
  height: auto;
  padding: 10px;
  font-size: 16px;
}

.form-signin .form-control:focus {
  z-index: 2;
}

.form-signin input[type="email"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}

.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}

#btnGetResource {
  display: none;
}
</style>
</head>

<body class="text-center">
  <main class="form-signin">
    <form method="post" action="authenticate.php" id="frmLogin">
      <h1 class="h3 mb-3 fw-normal">Login In</h1>

      <label for="inputEmail" class="visually-hidden">Email address</label>
      <input type="text" id="inputEmail" class="form-control" placeholder="Email address or username" required
        autofocus="">

      <label for="inputPassword" class="visually-hidden">Password</label>
      <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>

      <div class="checkbox mb-3">
        <label>
          <input type="checkbox" value="remember-me"> Remember me
        </label>
      </div>

      <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>

      <p class="mt-5 mb-3 text-muted">&copy; 2017-2021</p>
    </form>

    <button id="btnGetResource">Get current timestamp</button>
  </main>

  <script>
    const store = {};
    const loginButton = document.querySelector('#frmLogin');
    const btnGetResource = document.querySelector('#btnGetResource');
    const form = document.forms[0];

    // Inserts the jwt to the store object
    store.setJWT = function (data) {
      this.JWT = data;
    };

    loginButton.addEventListener('submit', async (e) => {
      e.preventDefault();

      const res = await fetch('<?php echo base_url('jwtauth/authenticate');?>', {
        method: 'POST',
        headers: {
          'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8'
        },
        body: JSON.stringify({
          username: form.inputEmail.value,
          password: form.inputPassword.value
        })
      });

      if (res.status >= 200 && res.status <= 299) {
        const jwt = await res.text();
        store.setJWT(jwt);
        frmLogin.style.display = 'none';
        btnGetResource.style.display = 'block';
      } else {
        // Handle errors
        console.log(res.status, res.statusText);
      }
    });

    btnGetResource.addEventListener('click', async (e) => {
      const res = await fetch('<?php echo base_url('jwtauth/resource');?>', {
        headers: {
          'Authorization': `Bearer ${store.JWT}`
        }
      });
      const timeStamp = await res.text();
      console.log(timeStamp);
    });
  </script>
</body>

</html>