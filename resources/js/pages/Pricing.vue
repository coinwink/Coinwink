<script setup>
import Widget from "../widget/Widget.vue";
import { ref } from "vue";

let subs = ref(window.subs);
let status = ref(window.status);
let plan = ref(window.subs_plan);

const userLoggedIn = window.userLoggedIn;
</script>

<template>
  <widget :title="'PRICING'">
    <div class="content" style="text-align: center">
      <h4>Coinwink Plans</h4>
      <div style="height: 5px"></div>

      <!-- <div class="rounded-border">
        <b>Free</b><br />
        Without account<br><br />
        <div style="padding-left: 70px; margin-bottom: 16px">
          <div style="float: left; width: 12px">
            <svg viewBox="0 0 512 444.03">
              <polygon
                points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
                style="fill: #777777"
              ></polygon>
            </svg>
          </div>
          <div style="float: left; margin-left: 7px">5 email alerts</div>
        </div>
      </div> -->

      <div class="rounded-border">
        <b>Free</b><br><br>
        <div style="padding-left: 40px; margin-bottom: 16px">
          <div style="float: left; width: 12px">
            <svg viewBox="0 0 512 444.03">
              <polygon
                points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
                style="fill: #777777"
              ></polygon>
            </svg>
          </div>
          <div style="float: left; margin-left: 7px">5 active Email alerts</div>
          <div style="clear: both; height: 3px"></div>
          <div style="float: left; width: 12px">
            <svg viewBox="0 0 512 444.03">
              <polygon
                points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
                style="fill: #777777"
              ></polygon>
            </svg>
          </div>
          <div style="float: left; margin-left: 7px">5 active Telegram alerts</div>
          <!-- <div style="clear: both; height: 3px"></div>
          <div style="float: left; width: 12px">
            <svg viewBox="0 0 512 444.03">
              <polygon
                points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
                style="fill: #777777"
              ></polygon>
            </svg>
          </div>
          <div style="float: left; margin-left: 7px">5 active Portfolio alerts</div> -->
          <div style="clear: both; height: 3px"></div>
          <div style="float: left; width: 12px">
            <svg viewBox="0 0 512 444.03">
              <polygon
                points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
                style="fill: #777777"
              ></polygon>
            </svg>
          </div>
          <div style="float: left; margin-left: 7px">5 coins in Portfolio</div>
          <div style="clear: both; height: 3px"></div>
          <div style="float: left; width: 12px">
            <svg viewBox="0 0 512 444.03">
              <polygon
                points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
                style="fill: #777777"
              ></polygon>
            </svg>
          </div>
          <div style="float: left; margin-left: 7px">5 coins in Watchlist</div>
        </div>
      </div>

      <div class="rounded-border">
        <b>Standard</b><br><br>
        <div style="padding-left: 40px; margin-bottom: 18px">
          <div style="float: left; width: 12px">
            <svg viewBox="0 0 512 444.03">
              <polygon
                points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
                style="fill: #777777"
              ></polygon>
            </svg>
          </div>
          <div style="float: left; margin-left: 7px">
            Unlimited Email alerts
          </div>
          <div style="clear: both; height: 3px"></div>
          <div style="float: left; width: 12px">
            <svg viewBox="0 0 512 444.03">
              <polygon
                points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
                style="fill: #777777"
              ></polygon>
            </svg>
          </div>
          <div style="float: left; margin-left: 7px">
            Unlimited Telegram alerts
          </div>
          <!-- <div style="clear: both; height: 3px"></div>
          <div style="float: left; width: 12px">
            <svg viewBox="0 0 512 444.03">
              <polygon
                points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
                style="fill: #777777"
              ></polygon>
            </svg>
          </div>
          <div style="float: left; margin-left: 7px">
            Unlimited Portfolio alerts
          </div> -->
          <div style="clear: both; height: 3px"></div>
          <div style="float: left; width: 12px">
            <svg viewBox="0 0 512 444.03">
              <polygon
                points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
                style="fill: #777777"
              ></polygon>
            </svg>
          </div>
          <div style="float: left; margin-left: 7px">
            Unlimited coins in Portfolio
          </div>
          <div style="clear: both; height: 3px"></div>
          <div style="float: left; width: 12px">
            <svg viewBox="0 0 512 444.03">
              <polygon
                points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
                style="fill: #777777"
              ></polygon>
            </svg>
          </div>
          <div style="float: left; margin-left: 7px">
            Unlimited coins in Watchlist
          </div>
        </div>
        <br />
        <b>$8&nbsp;/&nbsp;month</b>
      </div>

      <div class="rounded-border">
        <b>Premium</b><br><br>
        <div style="padding-left: 40px; margin-bottom: 18px">
          <div style="float: left; width: 12px">
            <svg viewBox="0 0 512 444.03">
              <polygon
                points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
                style="fill: #777777"
              ></polygon>
            </svg>
          </div>
          <div style="float: left; margin-left: 7px">
            Unlimited Email alerts
          </div>
          <div style="clear: both; height: 3px"></div>
          <div style="float: left; width: 12px">
            <svg viewBox="0 0 512 444.03">
              <polygon
                points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
                style="fill: #777777"
              ></polygon>
            </svg>
          </div>
          <div style="float: left; margin-left: 7px">
            Unlimited Telegram alerts
          </div>
          <!-- <div style="clear: both; height: 3px"></div>
          <div style="float: left; width: 12px">
            <svg viewBox="0 0 512 444.03">
              <polygon
                points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
                style="fill: #777777"
              ></polygon>
            </svg>
          </div>
          <div style="float: left; margin-left: 7px">
            Unlimited Portfolio alerts
          </div> -->
          <div style="clear: both; height: 3px"></div>
          <div style="float: left; width: 12px">
            <svg viewBox="0 0 512 444.03">
              <polygon
                points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
                style="fill: #777777"
              ></polygon>
            </svg>
          </div>
          <div style="float: left; margin-left: 7px">
            Unlimited coins in Portfolio
          </div>
          <div style="clear: both; height: 3px"></div>
          <div style="float: left; width: 12px">
            <svg viewBox="0 0 512 444.03">
              <polygon
                points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
                style="fill: #777777"
              ></polygon>
            </svg>
          </div>
          <div style="float: left; margin-left: 7px">
            Unlimited coins in Watchlist
          </div>
          <div style="clear: both; height: 3px"></div>
          <div style="float: left; width: 12px">
            <svg viewBox="0 0 512 444.03">
              <polygon
                points="202.62 444.03 0 257.38 70.51 180.82 191.97 292.67 431.44 0 512 65.92 202.62 444.03"
                style="fill: #777777"
              ></polygon>
            </svg>
          </div>
          <div style="float: left; margin-left: 7px">
            100 SMS alerts per month
          </div>
        </div>
        <br />
        <b>$12&nbsp;/&nbsp;month</b>
      </div>
      <div style="height: 5px"></div>
      <div v-if="userLoggedIn">
        <span style="font-size: 14px"
          >Your current plan:
          <b v-if="subs == 0 && status != 'suspended'">Free</b>
          <b v-else-if="subs == 1 && status == 'active' && plan == 'premium'">Premium</b>
          <b v-else-if="subs == 1 && status == 'active' && plan == 'standard'">Standard</b>
          <b v-else-if="subs == 1 && status == 'cancelled'">Cancelled</b>
          <b v-else-if="subs == 0 && status == 'suspended'">Suspended</b>
        </span>
        <div style="height: 15px"></div>
        <router-link v-if="subs == 0 && status != 'suspended'" to="/subscription" class="blacklink"
          >Upgrade</router-link
        >
        <router-link to="/account" v-else class="blacklink">Account</router-link
        ><br />
        <div style="height: 5px"></div>
      </div>
      <div v-else>
        <router-link to="/register" class="blacklink"
          >Create Account</router-link
        >
      </div>
      <div style="height: 3px"></div>
    </div>
  </widget>
</template>