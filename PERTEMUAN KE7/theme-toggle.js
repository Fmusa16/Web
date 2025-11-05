// theme-toggle.js
// Simple theme toggle that switches between light (default) and dark themes
// Persists choice in localStorage under key 'site-theme'

(function(){
  const STORAGE_KEY = 'site-theme';
  const DARK = 'dark';
  const LIGHT = 'light';

  function applyTheme(theme){
    if(theme === DARK){
      document.documentElement.setAttribute('data-theme', 'dark');
    } else {
      document.documentElement.removeAttribute('data-theme');
    }
  }

  function saveTheme(theme){
    try{ localStorage.setItem(STORAGE_KEY, theme); }catch(e){}
  }

  function loadTheme(){
    try{ return localStorage.getItem(STORAGE_KEY) || LIGHT; }catch(e){ return LIGHT; }
  }

  function toggleTheme(){
    const current = loadTheme();
    const next = current === DARK ? LIGHT : DARK;
    applyTheme(next);
    saveTheme(next);
    updateToggleButton(next);
  }

  function moonSVG(){
    return '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" fill="currentColor"/></svg>';
  }

  function sunSVG(){
    return '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M6.76 4.84l-1.8-1.79-1.41 1.41 1.79 1.8 1.42-1.42zM1 13h3v-2H1v2zm10-9h2V1h-2v3zm7.45 2.45l1.79-1.8-1.41-1.41-1.8 1.79 1.42 1.42zM17 13v-2h-2v2h2zm-5 8h2v-3h-2v3zm6.24-3.76l1.8 1.79 1.41-1.41-1.79-1.8-1.42 1.42zM6.76 19.16l-1.42 1.42 1.8 1.79 1.41-1.41-1.79-1.8zM12 8a4 4 0 100 8 4 4 0 000-8z" fill="currentColor"/></svg>';
  }

  function updateToggleButton(theme){
    const btn = document.getElementById('theme-toggle-btn');
    if(!btn) return;
    if(theme === DARK){
      btn.innerHTML = sunSVG();
      btn.setAttribute('aria-pressed','true');
      btn.setAttribute('title','Switch to light theme');
      btn.setAttribute('aria-label','Switch to light theme');
    } else {
      btn.innerHTML = moonSVG();
      btn.setAttribute('aria-pressed','false');
      btn.setAttribute('title','Switch to dark theme');
      btn.setAttribute('aria-label','Switch to dark theme');
    }
  }

  // Initialize
  document.addEventListener('DOMContentLoaded', function(){
    const theme = loadTheme();
    applyTheme(theme === DARK ? DARK : LIGHT);
    updateToggleButton(theme === DARK ? DARK : LIGHT);

    const btn = document.getElementById('theme-toggle-btn');
    if(btn){ btn.addEventListener('click', toggleTheme); }

    document.addEventListener('keydown', function(e){
      if(e.key && e.key.toLowerCase() === 't'){
        const active = document.activeElement;
        if(active && (active.tagName === 'INPUT' || active.tagName === 'TEXTAREA' || active.isContentEditable)) return;
        toggleTheme();
      }
    });
  });
})();
