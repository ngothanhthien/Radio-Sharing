/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors:{
        'callToActionFill':'var(--call-to-action-fill)',
        'callToActionFillHover':'var(--call-to-action-fill-hover)',
        'callToAction':'var(--call-to-action)',
        'primaryButton':'var(--primary-button)',
        'textPrimary':'var(--text-primary)',
        'textDisable':'var(--text-disable)',
        'textSecondary':'var(--text-secondary)',
        'contentBackground':'var(--content-bg)',
        'sidebarItemHover':'var(--sidebarItem-hover)',
        'sidebarItemFocusHover':'var(--sidebarItem-focus-hover)',
        'sidebarItemFocus':'var(--sidebarItem-focus)',
      }
    },
  },
  plugins: [],
}
