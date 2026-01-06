(function(){
  const loginForm = document.getElementById('loginForm');
  const registerForm = document.getElementById('registerForm');
  const form = loginForm || registerForm;
  if(!form) return;
  const username = document.getElementById('username');
  const password = document.getElementById('password');
  const clientError = document.getElementById('clientError');
  const authCard = document.getElementById('authCard');
  const serverError = document.getElementById('serverError');

  function showClientError(msg){
    clientError.textContent = msg;
    clientError.classList.add('client-alert');
    clientError.style.display = 'block';
    authCard.classList.remove('shake');
    // trigger reflow then add class
    void authCard.offsetWidth;
    authCard.classList.add('shake');
    if(serverError) serverError.style.display='none';
  }
  form.addEventListener('submit', function(e){
    clientError.style.display = 'none';
    // login form validation
    if(loginForm){
      if(!username.value.trim()){
        e.preventDefault();
        showClientError('Username harus diisi');
        username.focus();
        return;
      }
      if(password.value.length < 6){
        e.preventDefault();
        showClientError('Password minimal 6 karakter');
        password.focus();
        return;
      }
    }

    // register form validation
    if(registerForm){
      const nama = document.getElementById('nama');
      if(!nama || !nama.value.trim()){
        e.preventDefault();
        showClientError('Nama lengkap wajib diisi');
        if(nama) nama.focus();
        return;
      }
      if(!username || !username.value.trim()){
        e.preventDefault();
        showClientError('Username wajib diisi');
        if(username) username.focus();
        return;
      }
      if(!password || password.value.length < 6){
        e.preventDefault();
        showClientError('Password minimal 6 karakter');
        if(password) password.focus();
        return;
      }
    }

    // allow submit
  });

  // show/hide password
  const toggle = document.getElementById('togglePwd');
  if(toggle){
    toggle.addEventListener('click', function(){
      if(password.type === 'password'){
        password.type = 'text';
        toggle.textContent = '🙈';
      } else {
        password.type = 'password';
        toggle.textContent = '👁️';
      }
      password.focus();
    });
  }
})();
