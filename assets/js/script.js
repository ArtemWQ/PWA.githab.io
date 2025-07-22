document.addEventListener('DOMContentLoaded', function() {
  // Элементы формы
  const form = document.getElementById('bike-selector');
  const steps = document.querySelectorAll('.selector-step');
  const prevBtn = document.querySelector('.btn-prev');
  const nextBtn = document.querySelector('.btn-next');
  const submitBtn = document.querySelector('.btn-submit');
  let currentStep = 0;
  
  // Слайдер роста
  const heightSlider = document.querySelector('#height-slider');
  const currentHeightValue = document.querySelector('#slider-current-value');
  
  // Обновление значения слайдера
  heightSlider.addEventListener('input', function() {
    currentHeightValue.textContent = this.value;
  });
  
  // Навигация по шагам
  function showStep(stepIndex) {
    steps.forEach((step, index) => {
      step.classList.toggle('active', index === stepIndex);
    });
    
    prevBtn.disabled = stepIndex === 0;
    nextBtn.style.display = stepIndex === steps.length - 1 ? 'none' : 'block';
    submitBtn.style.display = stepIndex === steps.length - 1 ? 'block' : 'none';
  }
  
  // Проверка валидности текущего шага
  function validateStep(stepIndex) {
    let isValid = true;
    
    if (stepIndex === 0) {
      const bikeTypeSelected = form.querySelector('input[name="bike_type"]:checked');
      if (!bikeTypeSelected) {
        isValid = false;
        alert('Пожалуйста, выберите тип велосипеда');
      }
    }
    
    return isValid;
  }
  
  // Кнопка "Далее"
  nextBtn.addEventListener('click', function() {
    if (validateStep(currentStep)) {
      currentStep++;
      showStep(currentStep);
    }
  });
  
  // Кнопка "Назад"
  prevBtn.addEventListener('click', function() {
    currentStep--;
    showStep(currentStep);
  });
  
  // Обработка выбора карточек
  document.querySelectorAll('.option-card').forEach(card => {
    card.addEventListener('click', function() {
      const input = this.querySelector('input');
      input.checked = true;
      
      document.querySelectorAll('.option-card').forEach(c => {
        c.classList.remove('selected');
      });
      
      this.classList.add('selected');
    });
  });
  
  // Инициализация формы
  showStep(0);
  
  // Отправка формы
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);
    
    window.location.href = 'result.php?' + params.toString();
  });

  // Регистрация Service Worker
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
      navigator.serviceWorker.register('/sw.js')
        .then(registration => {
          console.log('ServiceWorker registered');
        })
        .catch(err => {
          console.log('ServiceWorker registration failed: ', err);
        });
    });
  }
});