document.addEventListener('DOMContentLoaded', () => {
  const navToggle = document.querySelector('.nav-toggle');
  const navLinks = document.querySelector('.nav-links');
  if (navToggle && navLinks) {
    navToggle.addEventListener('click', () => navLinks.classList.toggle('open'));
  }

  document.querySelectorAll('form[data-validate]').forEach((form) => {
    form.addEventListener('submit', (event) => {
      const errors = [];
      form.querySelectorAll('[required]').forEach((field) => {
        if (!field.value.trim()) errors.push(`${field.dataset.label || field.name} is required.`);
      });

      form.querySelectorAll('input[type="email"]').forEach((field) => {
        if (field.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value)) errors.push('Enter a valid email address.');
      });

      const password = form.querySelector('input[name="password"]');
      const confirmPassword = form.querySelector('input[name="confirm_password"]');
      if (password && password.value && password.value.length < 8) errors.push('Password must be at least 8 characters.');
      if (password && confirmPassword && password.value !== confirmPassword.value) errors.push('Passwords must match.');

      const phone = form.querySelector('input[name="phone"]');
      if (phone && phone.value && !/^[0-9+\-\s()]{7,20}$/.test(phone.value)) errors.push('Enter a valid phone number.');

      if (errors.length) {
        event.preventDefault();
        showClientErrors(form, errors);
      }
    });
  });

  document.querySelectorAll('input[type="date"][data-future]').forEach((field) => {
    const today = new Date().toISOString().split('T')[0];
    field.min = today;
  });

  const personsInput = document.querySelector('[data-persons]');
  const totalOutput = document.querySelector('[data-total-output]');
  if (personsInput && totalOutput) {
    const price = Number(personsInput.dataset.price || 0);
    const max = Number(personsInput.dataset.max || 1);
    const formatter = new Intl.NumberFormat('en-LK', {
      style: 'currency',
      currency: 'LKR',
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    });
    const updateTotal = () => {
      let persons = Math.max(1, Math.min(max, Number(personsInput.value || 1)));
      personsInput.value = persons;
      totalOutput.textContent = formatter.format(persons * price);
    };
    personsInput.addEventListener('input', updateTotal);
    updateTotal();
  }

  const revealTargets = document.querySelectorAll(
    '.section, .card, .panel, .sidebar, .form-shell, .stat, table, .detail-image, .section-image, .itinerary-item'
  );

  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    revealTargets.forEach((target, index) => {
      target.classList.add('reveal-on-scroll');
      target.style.setProperty('--reveal-delay', `${Math.min(index % 6, 5) * 55}ms`);
      observer.observe(target);
    });
  } else {
    revealTargets.forEach((target) => target.classList.add('is-visible'));
  }
});

function showClientErrors(form, errors) {
  let box = form.querySelector('.client-errors');
  if (!box) {
    box = document.createElement('div');
    box.className = 'client-errors flash error';
    form.prepend(box);
  }
  box.innerHTML = errors.map((error) => `<p>${escapeHtml(error)}</p>`).join('');
}

function escapeHtml(value) {
  return value.replace(/[&<>"']/g, (char) => ({
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;',
  }[char]));
}
