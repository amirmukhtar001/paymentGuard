/**
 * Theme Switcher for Frest
 * Supports Light, Dark, and System preferences
 */

(function () {
  'use strict';

  // Get saved theme or default to light
  const getStoredTheme = () => localStorage.getItem('theme') || 'light';
  const setStoredTheme = theme => localStorage.setItem('theme', theme);

  // Get system preference
  const getSystemTheme = () => {
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  };

  // Apply theme to document
  const applyTheme = (theme) => {
    const html = document.documentElement;
    const effectiveTheme = theme === 'system' ? getSystemTheme() : theme;

    // Remove existing theme classes
    html.classList.remove('light-style', 'dark-style');

    // Add new theme class
    html.classList.add(`${effectiveTheme}-style`);

    // Update data-theme attribute
    if (effectiveTheme === 'dark') {
      html.setAttribute('data-theme', 'theme-dark');
    } else {
      html.setAttribute('data-theme', 'theme-default');
    }

    // Update icon in switcher button
    updateSwitcherIcon(effectiveTheme);
  };

  // Update the icon in the switcher button
  const updateSwitcherIcon = (theme) => {
    const icon = document.querySelector('.dropdown-style-switcher .bx');
    if (icon) {
      icon.className = 'bx bx-sm';
      if (theme === 'dark') {
        icon.classList.add('bx-moon');
      } else {
        icon.classList.add('bx-sun');
      }
    }
  };

  // Initialize theme on page load
  const initTheme = () => {
    const savedTheme = getStoredTheme();
    applyTheme(savedTheme);
  };

  // Handle theme changes
  const setupThemeSwitcher = () => {
    const themeSwitchers = document.querySelectorAll('.dropdown-styles .dropdown-item');

    themeSwitchers.forEach(switcher => {
      switcher.addEventListener('click', function () {
        const selectedTheme = this.getAttribute('data-theme');
        setStoredTheme(selectedTheme);
        applyTheme(selectedTheme);
      });
    });
  };

  // Listen for system theme changes (when user selects 'system')
  const watchSystemTheme = () => {
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
      const currentTheme = getStoredTheme();
      if (currentTheme === 'system') {
        applyTheme('system');
      }
    });
  };

  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
      initTheme();
      setupThemeSwitcher();
      watchSystemTheme();
    });
  } else {
    initTheme();
    setupThemeSwitcher();
    watchSystemTheme();
  }

})();
