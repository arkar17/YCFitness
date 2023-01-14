const toggleSwitch = document.querySelector('.theme');
const currentTheme = localStorage.getItem('theme');

if (currentTheme) {
    document.documentElement.setAttribute('data-theme', currentTheme);

    // if (currentTheme === 'dark') {
        toggleSwitch.value = currentTheme;
    // }
}else{
    localStorage.setItem('theme', 'light');
    document.documentElement.setAttribute('data-theme', "light");
    toggleSwitch.value = "light";
}

function switchTheme(e) {
    console.log(e.target.value)
    if (e.target.value === "dark") {
        document.documentElement.setAttribute('data-theme', 'dark');
        localStorage.setItem('theme', 'dark');
    }
    if(e.target.value === "pink"){
        document.documentElement.setAttribute('data-theme', 'pink');
        localStorage.setItem('theme', 'pink');
    }
    if(e.target.value === "light") {        document.documentElement.setAttribute('data-theme', 'light');
          localStorage.setItem('theme', 'light');
    }
}

toggleSwitch.addEventListener('change', switchTheme, false);


