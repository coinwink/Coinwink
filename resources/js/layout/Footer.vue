<template>
  <div style="height: 20px"></div>
  <footer style="text-align: center">
    <div style="margin: 0 auto; padding-top: 4px; padding-bottom: 10px" class="text-footer-links">
      <router-link to="/manage-alerts" class="whitelink link-manage-alerts">Alerts</router-link>
      &nbsp;|&nbsp;
      <router-link to="/account" class="whitelink">Account</router-link>
      &nbsp;|&nbsp;
      <router-link to="/portfolio" class="whitelink link-portfolio">Portfolio</router-link>
      &nbsp;|&nbsp;
      <router-link to="/watchlist" class="whitelink link-watchlist">Watchlist</router-link>
    </div>
    <div style="margin-top: 18px; font-size: 11px" class="text-footer-links">
      <router-link to="/about" class="whitelink link-about">About</router-link>&nbsp;|&nbsp;
      <router-link to="/pricing" class="whitelink link-pricing">Pricing</router-link>&nbsp;|&nbsp;
      <a href="/terms" class="whitelink link-terms" target="_blank">Terms</a>&nbsp;|&nbsp;
      <a href="/privacy" class="whitelink link-privacy" target="_blank">Privacy</a>&nbsp;|&nbsp;
      <!-- <router-link to="/press" class="whitelink link-press">Press</router-link>&nbsp;|&nbsp; -->
      <a href="https://coinwink.com/blog/" class="whitelink" target="_blank">Blog</a>&nbsp;|&nbsp;
      <router-link to="/contacts" class="whitelink link-contacts">Contacts</router-link>
    </div>
    <div style="height: 15px"></div>
    <span style="line-height: 150%; font-size: 10px" class="text-footer">
      Crypto Price Alerts, Watchlist and Portfolio Tracking App
      <br />
      Privacy-Focused, Based on CoinMarketCap
    </span>
    <div style="height: 40px"></div>
  </footer>
</template>