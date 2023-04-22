<template>
  <div>
    <template v-if="loadingIndex">
      <div class="text-center">
        <i class="el-icon-loading data-loading"></i>
      </div>
    </template>
    <template v-else>
      <div class="case">
        <div class="case-inner">
          <div class="case-title">
            {{box.name}}
          </div>
          <div class="case-price">$ {{total}}</div>
          <template v-if="open_status">
            <div class="roulette-bg"></div>
            <div ref="roulette" :class="'roulette roulette' + num">
              <div class="roulette-item" v-for="(item, index) in box_ornaments_arr">
                <div ref="roulette" class="roulette-list "
                     :style="roll == 1 ? 'transition: all 8s cubic-bezier(0.15, 0.89, 0.19, 0.99) 0s;transform: translateX(' + item.translate_x + 'px);' : ''">
                  <div :class="'roulette-list-item roulette-list-item' + item.color"
                       v-for="(item, index) in item.box_ornaments">
                    <div class="roulette-list-item-wrap">
                      <svg class="roulette-list-item-polygon" viewBox="0 0 379 379">
                        <path fill="none"
                              d="M285.81 285.925l-96.25 39.866-96.25-39.866-39.87-96.243L93.31 93.44l96.251-39.866 96.25 39.866 39.869 96.243z"></path>
                        <path fill="none"
                              d="M322.11 322.223l-132.55 54.9-132.55-54.9-54.905-132.54L57.01 57.14l132.55-54.9 132.55 54.9 54.905 132.541z"></path>
                      </svg>
                      <img class="roulette-list-item-img" :src="img_url + item.img" alt="">
                    </div>
                    <div class="roulette-list-item-info">
                      <div class="roulette-list-item-name">{{item.name}}</div>
                    </div>
                  </div>
                </div>
                <div class="roulette-decor"></div>
              </div>
            </div>
          </template>
          <template v-else>
            <div class="case-wrap">
              <div class="case-product">
                <img class="case-img" v-show="box.img" :src="img_url + box.img" :alt="box.name">
              </div>
            </div>
          </template>
        </div>
        <div class="case-inner">
          <div v-if="!open_status" class="case-tab">
            <div :class="'case-tab-item ' + [num == 1 ? 'active' : '']" @click="num_switch(1)">1</div>
            <div :class="'case-tab-item ' + [num == 2 ? 'active' : '']" @click="num_switch(2)">2</div>
            <div :class="'case-tab-item ' + [num == 3 ? 'active' : '']" @click="num_switch(3)">3</div>
            <div :class="'case-tab-item ' + [num == 4 ? 'active' : '']" @click="num_switch(4)">4</div>
            <div :class="'case-tab-item ' + [num == 5 ? 'active' : '']" @click="num_switch(5)">5</div>
          </div>
          <div v-if="!open_status" class="case-btn" @click="open">
            打开
          </div>
          <div v-if="open_status" class="case-loader">
            <span>打开</span>
            <img src="@/assets/img/loader.svg">
          </div>
        </div>
        <div class="case-contains">
          <div class="case-inner">
            <div class="case-contains-title">
              <div :class="'case-contains-title-item ' + [title_index == 1 ? 'active' : '']" @click="title_switch(1)">箱子内容</div>
              <div :class="'case-contains-title-item ' + [title_index == 2 ? 'active' : '']" @click="title_switch(2)">历史掉落</div>
            </div>
          </div>
          <div class="case-items">
            <div class="case-inner">
              <template v-if="title_index == 1">
                <div class="case-items-wrap">
                  <div class="live-item" v-for="(item, index) in new_box_ornaments">
                    <div class="live-item-probability">
                      <div>机会: {{item.total_probability}}%</div>
                      <div class="live-item-price" v-if="item.arr.length == 1">$ {{item.arr[0].open_price}}</div>
                      <div class="live-item-chance-dropdown" v-else>
                        <div class="live-item-chance-title">物品几率</div>
                        <table class="live-item-chance-table">
                          <tbody>
                          <tr>
                            <th>物品</th>
                            <th>几率</th>
                            <th>价值</th>
                          </tr>
                          <tr v-for="(arr_item, arr_index) in item.arr">
                            <td>
                              {{arr_item.name}}
                              <template v-if="arr_item.appid == 730">
                                <template v-if="abrasion(arr_item.abrasion)">
                                  ({{ abrasion(arr_item.abrasion) }})
                                </template>
                                <template v-if="quality(arr_item.quality, 2)">
                                  ({{ quality(arr_item.quality, 2) }})
                                </template>
                              </template>
                              <template v-else>({{ quality2(arr_item.quality2) }})</template>
                            </td>
                            <td>{{arr_item.probability}}%</td>
                            <td>$ {{arr_item.open_price}}</td>
                          </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div :class="'roulette-list-item' + item.arr[0].color">
                      <div class="o-item roulette-list-item-wrap" style="width: 130px;height: 130px;background: transparent;">
                        <svg class="roulette-list-item-polygon" viewBox="0 0 379 379">
                          <path fill="none"
                                d="M285.81 285.925l-96.25 39.866-96.25-39.866-39.87-96.243L93.31 93.44l96.251-39.866 96.25 39.866 39.869 96.243z"></path>
                          <path fill="none"
                                d="M322.11 322.223l-132.55 54.9-132.55-54.9-54.905-132.54L57.01 57.14l132.55-54.9 132.55 54.9 54.905 132.541z"></path>
                        </svg>
                        <div class="o-body">
                          <svg class="o-bg" viewBox="0 0 87.3 107.3">
                            <path d="M83.1 107.3l-61.8-74L87.3 0l-4.2 107.3z" fill-opacity=".04"></path>
                            <path d="M67.7 70.1l-57.5 32.6 11.3-61.3 46.2 28.7z" fill-opacity=".13"></path>
                            <path d="M67.3 63.5L27 74.5l14.3-37.4 26 26.4z" fill-opacity=".02"></path>
                            <path d="M85.5 79.1L51 106.6l29.6-37.4 4.9 9.9z" fill-opacity=".2"></path>
                            <path d="M19.6 72.9L0 52.9l24.1 2-4.5 18z" fill-opacity=".07"></path>
                          </svg>
                          <img class="o-img" :src="img_url + item.arr[0].img" alt="">
                        </div>
                      </div>
                      <div class="o-name">{{item.arr[0].name}}</div>
                      <div class="o-abrasion" v-if="item.arr[0].appid == 730">{{ abrasion(item.arr[0].abrasion) }}{{
                          quality(item.arr[0].quality, 3) }}
                      </div>
                      <div class="o-abrasion" v-else>{{ quality2(item.arr[0].quality2) }}</div>
                      <div class="o-abrasion"><span>累计: {{ item.arr[0].cumulative }}</span><span>上次: {{ item.arr[0].last_time }}</span></div>
                    </div>
                  </div>
                </div>
              </template>
              <template v-if="title_index == 2">
                <div class="case-items-wrap">
                  <div class="live-item" v-for="(item, index) in new_box_ornaments2">
                    <div class="live-item-probability">
                      <div class="live-item-price">$ {{item.open_price}}</div>
                    </div>
                    <div :class="'roulette-list-item' + item.color">
                      <div class="o-item roulette-list-item-wrap" style="width: 130px;height: 130px;background: transparent;">
                        <svg class="roulette-list-item-polygon" viewBox="0 0 379 379">
                          <path fill="none"
                                d="M285.81 285.925l-96.25 39.866-96.25-39.866-39.87-96.243L93.31 93.44l96.251-39.866 96.25 39.866 39.869 96.243z"></path>
                          <path fill="none"
                                d="M322.11 322.223l-132.55 54.9-132.55-54.9-54.905-132.54L57.01 57.14l132.55-54.9 132.55 54.9 54.905 132.541z"></path>
                        </svg>
                        <div class="o-body">
                          <svg class="o-bg" viewBox="0 0 87.3 107.3">
                            <path d="M83.1 107.3l-61.8-74L87.3 0l-4.2 107.3z" fill-opacity=".04"></path>
                            <path d="M67.7 70.1l-57.5 32.6 11.3-61.3 46.2 28.7z" fill-opacity=".13"></path>
                            <path d="M67.3 63.5L27 74.5l14.3-37.4 26 26.4z" fill-opacity=".02"></path>
                            <path d="M85.5 79.1L51 106.6l29.6-37.4 4.9 9.9z" fill-opacity=".2"></path>
                            <path d="M19.6 72.9L0 52.9l24.1 2-4.5 18z" fill-opacity=".07"></path>
                          </svg>
                          <img class="o-img" :src="img_url + item.img" alt="">
                        </div>
                      </div>
                      <div class="o-name">{{item.name}}</div>
                      <div class="o-abrasion" v-if="item.appid == 730">{{ abrasion(item.abrasion) }}{{
                          quality(item.quality, 3) }}
                      </div>
                      <div class="o-abrasion" v-else>{{ quality2(item.quality2) }}</div>
                    </div>
                  </div>
                </div>
              </template>
            </div>
          </div>
        </div>
      </div>
    </template>
    <el-dialog
      v-if="ornaments_arr.length > 0"
      :class="'result-dialog result-dialog' + ornaments_arr[0].color"
      title="您赢了!"
      append-to-body
      :visible.sync="result_page"
      :close-on-click-modal="false"
      :lock-scroll="false"
      center>
      <div class="result-info">
        <img class="result-item" :src="img_url + ornaments_arr[0].img">
        <svg class="result-bg" viewBox="0 0 806 990">
          <path fill-opacity=".07" d="M766.744 989.902L197.003 307.525 805.588.645z"></path>
          <path fill-opacity=".13" d="M624.926 646.62L94.815 946.72 199.353 381.5z"></path>
          <path fill-opacity=".02" d="M620.999 585.644L249.941 687.265 381.708 342.6z"></path>
          <path fill-opacity=".2" d="M788.776 729.591l-317.744 253.8L743.77 638.707z"></path>
          <path fill-opacity=".07" d="M181.021 672.364L.614 487.927l222.239 18.038z"></path>
        </svg>
        <svg class="result-polygon" viewBox="0 0 534 534">
          <path fill="none"
                d="M449.786 449.807l-182.785 75.716-182.787-75.716-75.713-182.79L84.214 84.225 267.001 8.51l182.785 75.715 75.713 182.792-75.713 182.79z"></path>
        </svg>
        <svg class="result-polygon result-polygon-lg" viewBox="0 0 722 722">
          <path fill="none"
                d="M612.723 612.746l-251.722 104.27-251.723-104.27L5.011 361.017l104.267-251.73L361.001 5.017l251.722 104.27 104.267 251.73-104.267 251.729z"></path>
        </svg>
        <div class="result-light"></div>
      </div>
      <div class="result-title"></div>
      <div class="result-name">{{ornaments_arr[0].name}}</div>
      <div class="result-abrasion" v-if="ornaments_arr[0].appid == 730">{{ abrasion(ornaments_arr[0].abrasion) }}{{
        quality(ornaments_arr[0].quality,
        3) }}
      </div>
      <div class="result-abrasion" v-else>{{ quality2(ornaments_arr[0].quality2) }}</div>
      <div class="result-buttons">
        <button class="result-btn" @click="again">再试一次</button>
        <button class="result-btn result-btn2" @click="sell">出售 ${{ornaments_arr[0].open_price}}</button>
      </div>
    </el-dialog>
    <el-dialog
      v-if="ornaments_arr.length > 0"
      class="result-dialogs"
      append-to-body
      :visible.sync="result_page2"
      :close-on-click-modal="false"
      :lock-scroll="false"
      center>
      <template slot="title">
        <div class="header-main-title">您赢了!</div>
        <div class="header-sub-title">{{ornaments_arr.length}}件饰品 <span class="price">$ {{ornaments_arr_price_total}}</span></div>
      </template>
      <div class="result-info">
        <div :class="'live-item ' + [item.selected ? 'active' : '']"
             v-for="(item, index) in ornaments_arr" @click="ornaments_select(index)">
          <div class="live-item-check" v-if="item.selected">
            <svg class="icon-check" viewBox="0 0 34 25">
              <g>
                <g>
                  <path
                    d="M30.802-.006l-18.66 18.674-8.944-8.931L0 12.944 10.564 23.5l1.579 1.502L13.72 23.5 34 3.16z"/>
                </g>
              </g>
            </svg>
          </div>
          <div :class="color(item.color, 3)">
            <div class="o-item">
              <div class="o-body">
                <svg class="o-bg" viewBox="0 0 87.3 107.3">
                  <path d="M83.1 107.3l-61.8-74L87.3 0l-4.2 107.3z" fill-opacity=".04"></path>
                  <path d="M67.7 70.1l-57.5 32.6 11.3-61.3 46.2 28.7z" fill-opacity=".13"></path>
                  <path d="M67.3 63.5L27 74.5l14.3-37.4 26 26.4z" fill-opacity=".02"></path>
                  <path d="M85.5 79.1L51 106.6l29.6-37.4 4.9 9.9z" fill-opacity=".2"></path>
                  <path d="M19.6 72.9L0 52.9l24.1 2-4.5 18z" fill-opacity=".07"></path>
                </svg>
                <img class="o-img" :src="img_url + item.img" alt="">
              </div>
            </div>
            <div class="live-item-price">
              <span>$ {{item.open_price}}</span>
            </div>
            <div class="o-name">{{item.name}}</div>
            <div class="o-abrasion" v-if="item.appid == 730">{{ abrasion(item.abrasion) }}{{ quality(item.quality,
              3) }}
            </div>
            <div class="o-abrasion" v-else>{{ quality2(item.quality2) }}</div>
          </div>
        </div>
      </div>
      <div class="result-buttons">
        <button class="result-btn" @click="again">再试一次</button>
        <button class="result-btn result-btn2" @click="sell">出售所有 ${{ornaments_arr_price_total}}</button>
        <button :class="'result-btn result-btn2 ' + [ornaments_arr_selected_num == 0 ? 'result-btn-disabled' : '']" @click="sell2">出售选择的饰品</button>
      </div>
    </el-dialog>
    <div style="display: none;">
      <audio ref="audio" :src="tick" loop="loop">你的浏览器不支持audio标签</audio>
    </div>
  </div>
</template>

<script>
  import {ttGet, ttPost, ttWebSocket, abrasion, quality, quality2, color, rand} from "@/utils/util.js";

  export default {
    data() {
      return {
        img_url: this.$img_url,
        loadingIndex: true,
        id: '',
        box: {},
        box_ornaments_arr: [],
        new_box_ornaments: [],
        new_box_ornaments2: [],
        num: 1,
        total: 0,
        open_status: false, //开启状态 用于显示滚动页面
        roll: false, //滚动状态 开启后开始滚动
        result_page: false, //结果页面
        result_page2: false, //结果页面2 多数据显示
        btn_status: true, //按钮状态 为真时才能点击开启
        bgmId: 0,
        screen_width: 0,
        ornaments_arr: [],
        tick: this.$server_url + '/public/static/audio/tick.mp3',
        title_index: 1,
      }
    },
    computed: {
      roulette_list_item_weight: function () {
        let roulette_list_item_weight = 250;
        if (this.num == 3) {
          roulette_list_item_weight = 200;
        } else if (this.num == 4) {
          roulette_list_item_weight = 180;
        } else if (this.num == 5) {
          roulette_list_item_weight = 160;
        }
        return roulette_list_item_weight;
      },
      ornaments_arr_price_total: function () {
        let ornaments_arr_price_total = 0;
        let ornaments_arr = this.ornaments_arr;
        for (let x in ornaments_arr) {
          ornaments_arr_price_total += Number(ornaments_arr[x].open_price || 0);
        }
        return ornaments_arr_price_total.toFixed(2);
      },
      ornaments_arr_selected_num: function () {
        let ornaments_arr_selected_num = 0;
        let ornaments_arr = this.ornaments_arr;
        for (let x in ornaments_arr) {
          if (ornaments_arr[x].selected) ornaments_arr_selected_num++;
        }
        return ornaments_arr_selected_num;
      }
    },
    mounted() {
      //获取屏幕尺寸
      this.screen_width = document.body.clientWidth;
      window.onresize = () => {
        //屏幕尺寸变化
        return (() => {
          this.screen_width = document.body.clientWidth;
        })();
      }
      this.id = encodeURIComponent(this.$route.params.id);
      this.get_box_details();
    },
    methods: {
      abrasion, quality, quality2, color,

      //获取箱子详情
      get_box_details() {
        let id = this.id;
        ttGet("/api.php/box/get_box_details", {id: id}, (res) => {
          this.loadingIndex = false;
          if (res.code == 200) {
            this.box = res.data.box;
            this.new_box_ornaments = res.data.new_box_ornaments;
            this.new_box_ornaments2 = res.data.new_box_ornaments2;
            this.total = res.data.box.price;
          } else {
            this.$message({
              message: res.msg,
              type: 'warning'
            });
          }
        });
      },

      //数量切换
      num_switch(num) {
        this.num = num;
        this.total = (this.box.price * num).toFixed(2);
      },

      //分布
      distribution(num) {
        let box_ornaments = this.box.box_ornaments;
        let ornaments_num = box_ornaments.length;
        let box_ornaments_arr = [];
        for (let i = 0; i < num; i++) {
          let item_box_ornaments = this.shuffle_array(box_ornaments);
          if (ornaments_num < 60) {
            do {
              item_box_ornaments = item_box_ornaments.concat(item_box_ornaments);
            } while (60 > (item_box_ornaments.length));
          }
          item_box_ornaments = item_box_ornaments.slice(0, 60);
          box_ornaments_arr.push({
            roll: 0,
            translate_x: 0,
            box_ornaments: this.shuffle_array(item_box_ornaments),
          });
        }
        this.box_ornaments_arr = box_ornaments_arr;
      },

      //随机打乱数组
      shuffle_array(arr) {
        arr = arr.slice();
        for (let i = arr.length - 1; i > 0; i--) {
          let j = Math.floor(Math.random() * (i + 1));
          let temp = arr[i];
          arr[i] = arr[j];
          arr[j] = temp;
        }
        return arr;
      },

      //开启箱子
      open() {
        if (!this.btn_status) return false;
        this.btn_status = false;
        let id = this.id;
        let num = this.num;
        let loading = this.$loading({
          spinner: 'el-icon-loading',
          background: 'rgb(0 0 0 / 0%)'
        });
        ttPost("/api.php/box/open", {id: id, num: num}, (res) => {
          loading.close();
          this.btn_status = true;
          if (res.code == 200) {
            this.distribution(num);
            setTimeout(() => {
              if (this.$refs.audio){
                this.$refs.audio.volume = 1;
                this.$refs.audio.playbackRate = 3.5;
                this.$refs.audio.play();
              }
            }, 500);
            setTimeout(() => {
              if (this.$refs.audio){
                this.$refs.audio.playbackRate = 3.2;
              }
            }, 2000);
            setTimeout(() => {
              if (this.$refs.audio){
                this.$refs.audio.playbackRate = 3;
                this.$refs.audio.volume = 0.8;
              }
            }, 3000);
            setTimeout(() => {
              if (this.$refs.audio){
                this.$refs.audio.playbackRate = 2.8;
                this.$refs.audio.volume = 0.6;
              }
            }, 4000);
            setTimeout(() => {
              if (this.$refs.audio){
                this.$refs.audio.playbackRate = 2;
              }
            }, 5000);
            setTimeout(() => {
              if (this.$refs.audio){
                this.$refs.audio.playbackRate = 1.5;
              }
            }, 6000);
            setTimeout(() => {
              if (this.$refs.audio){
                this.$refs.audio.playbackRate = 1;
              }
            }, 7000);
            setTimeout(() => {
              if (this.$refs.audio){
                this.$refs.audio.pause();
              }
            }, 8100);
            let ornaments_arr = res.data.ornaments_arr;
            let balance = res.data.balance;
            let box_ornaments_arr = this.box_ornaments_arr;
            this.open_status = true;
            this.ornaments_arr = ornaments_arr.map((item) => {
              item.selected = false;
              return item;
            });
            this.$emit('user_bean', {balance: balance});
            let random_total = rand(-(this.roulette_list_item_weight / 2) + 2, this.roulette_list_item_weight / 2 - 2);
            setTimeout(() => {
              for (let x in box_ornaments_arr) {
                let random = rand(30, 50);
                box_ornaments_arr[x].box_ornaments.splice(random, 0, ornaments_arr[x]);
                let translate_x = 0;
                let screen_width = this.screen_width;
                let roulette = this.$refs.roulette.clientWidth;
                let differ = screen_width - roulette;
                translate_x = (screen_width / 2) - (this.roulette_list_item_weight * random + this.roulette_list_item_weight / 2) - (differ / 2) - random_total;
                box_ornaments_arr[x].translate_x = translate_x;
              }
              this.roll = true;
            }, 500);
            setTimeout(() => {
              if (ornaments_arr.length > 1) {
                this.result_page2 = true;
              } else {
                this.result_page = true;
              }
              this.open_status = false;
              this.roll = false;
            }, 9000);
          } else {
            this.$message({
              message: res.msg,
              type: 'warning'
            });
          }
        });
      },

      //再试一次
      again() {
        this.result_page = false;
        this.result_page2 = false;
      },

      //出售饰品
      sell() {
        let ornaments_arr = this.ornaments_arr;
        let record_id = [];
        for (let x in ornaments_arr) {
          record_id.push(ornaments_arr[x].record_id);
        }
        let loading = this.$loading({
          spinner: 'el-icon-loading',
          background: 'rgb(0 0 0 / 0%)'
        });
        ttPost("/api.php/user/sell", {record_id: record_id}, (res) => {
          loading.close();
          if (res.code == 200) {
            this.result_page = false;
            this.result_page2 = false;
            this.$emit('user_bean', {balance: res.data.balance});
            this.$message({
              message: res.msg,
              type: 'success'
            });
          } else {
            this.$message({
              message: res.msg,
              type: 'warning'
            });
          }
        });
      },

      //单个饰品选择兑换
      ornaments_select(index) {
        this.ornaments_arr[index].selected = !this.ornaments_arr[index].selected;
      },

      //出售选中饰品
      sell2() {
        let ornaments_arr = this.ornaments_arr;
        let record_id = [];
        for (let x in ornaments_arr) {
          if (ornaments_arr[x].selected) record_id.push(ornaments_arr[x].record_id);
        }
        let loading = this.$loading({
          spinner: 'el-icon-loading',
          background: 'rgb(0 0 0 / 0%)'
        });
        ttPost("/api.php/user/sell", {record_id: record_id}, (res) => {
          loading.close();
          if (res.code == 200) {
            for (let i = 0; i < ornaments_arr.length;){
              if (ornaments_arr[i].selected){
                ornaments_arr.splice(i, 1);
              } else {
                i++;
              }
            }
            this.ornaments_arr = ornaments_arr;
            if (ornaments_arr.length == 0){
              this.result_page = false;
              this.result_page2 = false;
            }
            this.$emit('user_bean', {balance: res.data.balance});
            this.$message({
              message: res.msg,
              type: 'success'
            });
          } else {
            this.$message({
              message: res.msg,
              type: 'warning'
            });
          }
        });
      },

      //切换
      title_switch(title_index) {
        this.title_index = title_index;
      }
    }
  }
</script>

<style lang="scss" scoped>
  .vue-view {
    margin-bottom: 70px;
  }

  .case {
    text-align: center;
    padding-top: 80px;
    overflow: hidden;
  }

  .case-title {
    font-size: 27px;
    line-height: 1.2;
    color: #fff;
    font-weight: 500;
    margin-bottom: 8px;
    text-transform: capitalize;
  }

  .case-price {
    font-size: 17px;
    font-weight: 500;
    color: #7ef2ad;
    margin-bottom: 30px;
  }

  .case-wrap {
    position: relative;
    z-index: -1;
    height: 300px;
    background-color: #1b1b24;
    border-radius: 16px;
  }

  .case-product {
    display: inline-flex;
    flex-direction: column;
    justify-content: flex-start;
    height: 280px;
  }

  .case-img {
    width: 350px;
  }

  .case-tab {
    display: flex;
    justify-content: center;
    border-bottom: 1px solid #14162a;
  }

  .case-tab-item {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 70px;
    width: 38px;
    margin-bottom: -1px;
    color: #3a3958;
    font-size: 17px;
    font-weight: 500;
    transition: color .15s;
    cursor: pointer;
  }

  .case-tab-item:not(:last-child) {
    margin-right: 35px;
  }

  .case-tab-item.active {
    color: #cfcee9;
    border-bottom: 1px solid #f3a45d;
  }

  .case-tab-item:not(.case-tab-item.active):hover {
    color: #4e4d77;
  }

  .case-btn {
    margin: 20px auto 0;
    position: relative;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    align-items: center;
    justify-content: center;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    height: 62px;
    padding: 23px 28px;
    max-width: 316px;
    color: #392c22;
    font-weight: 700;
    font-size: 14px;
    text-align: center;
    text-transform: uppercase;
    background-color: #f3a45d;
    border-radius: 8px;
    -webkit-transition: color .3s ease,background-color .3s ease;
    transition: color .3s ease,background-color .3s ease;
    cursor: pointer;
  }

  .case-btn:hover {
    color: #684933;
    background-color: #fac26f;
  }

  .case-contains {
    margin-top: 50px;
  }

  .case-contains-title {
    position: relative;
    display: flex;
    align-items: center;
    height: 60px;
    line-height: 60px;
    text-align: left;
    font-weight: 500;
    color: #fff;
    font-size: 15px;
    background: linear-gradient(to bottom, rgba(16, 17, 32, .9), rgba(16, 17, 32, 0)) left 60% no-repeat #101120;
    overflow: hidden;
  }

  .case-contains-title-item {
    width: 150px;
    text-align: center;
    height: 100%;
    cursor: pointer;

    &.active {
      background-color: #15172c;
    }
  }

  .case-items {
    background-color: #15172c;
  }

  .case-items-wrap {
    display: flex;
    flex-wrap: wrap;
    border-left: 1px solid #1e2138;
  }

  .live-item {
    display: flex;
    flex-direction: column;
    flex: 0 0 14.28571%;
    max-width: 14.28571%;
    border: 1px solid #1e2138;
    border-left-color: transparent;
    border-top-color: transparent;
    background-color: #15172c;
    text-align: left;
    padding: 20px;
    position: relative;
  }

  .live-item-probability {
    position: absolute;
    top: -1px;
    left: -1px;
    right: -1px;
    padding: 5px 10px;
    background-color: rgba(48, 52, 88, .23);
    font-size: 10px;
    color: #a1a3b6;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
  }

  .live-item-chance-dropdown {
    background: rgba(26, 29, 51, .8);
    transition: visibility .3s, opacity .3s, transform .3s;
    position: absolute;
    z-index: 10;
    left: 50%;
    top: calc(100% + 13px);
    padding: 12px 5px;
    visibility: hidden;
    opacity: 0;
    transform: translate(-50%, -10px);
    -webkit-backdrop-filter: blur(2px);
    backdrop-filter: blur(2px);
    max-height: 182px;
    overflow-y: scroll;

    &::after {
      content: '';
      position: absolute;
      bottom: 100%;
      left: 50%;
      transform: translateX(-50%);
      border-bottom: 11px solid #1a1d33;
      border-left: 11px solid transparent;
      border-right: 11px solid transparent;
    }
  }

  .live-item-probability:hover .live-item-chance-dropdown {
    visibility: visible;
    opacity: 1;
    transform: translate(-50%, 0);
  }

  .live-item-chance-title {
    font-size: 11px;
    text-transform: uppercase;
    text-align: center;
  }

  .live-item-chance-table {
    text-transform: none;
    color: #fff;
    font-size: 12px;
    margin-bottom: 0;

    th {
      padding: 10px 10px 6px;
      font-weight: 700;
      text-align: left;
    }

    th:first-child {
      text-align: left;
    }

    td {
      padding: 4px 10px;
      text-align: left;
    }

    td:first-child {
      text-align: left;
      color: #b8bce1;
    }
  }

  .live-item-price {
    color: #7bdca2;
  }

  .live-item-img {
    width: 100%;
    max-width: 120px;
    margin-left: auto;
    margin-right: auto;
    margin-bottom: -10px;
  }

  .o-item {
    padding: 20px 0 10px;
  }

  .o-body {
    width: 100%;
    max-width: 120px;
    margin-left: auto;
    margin-right: auto;
  }

  .o-bg {
    width: 88px;
    height: 108px;
  }

  .roulette {
    background-color: #0d0e1c;
    margin-bottom: 30px;
  }

  .roulette1 {
    height: 300px;
  }

  .roulette2 {
    height: 614px;
  }

  .roulette3 {
    height: 549px;
  }

  .roulette4 {
    height: 633px;
  }

  .roulette5 {
    height: 646px;
  }

  .roulette-item {
    position: relative;
    max-width: 1410px;
    margin-left: auto;
    margin-right: auto;
    overflow: hidden;
    filter: drop-shadow(0 31px 53px #000);
  }

  .roulette-item::before {
    left: 0;
    background-image: linear-gradient(to right, #141627, rgba(20, 22, 39, .9) 100px, rgba(20, 22, 39, 0));
  }

  .roulette-item::after {
    right: 0;
    background-image: linear-gradient(to left, #141627, rgba(20, 22, 39, .9) 100px, rgba(20, 22, 39, 0));
  }

  .roulette-list {
    display: flex;
    box-shadow: 0 31px 53px rgb(8 9 18 / 67%);
  }

  .roulette-list-item {
    position: relative;
    flex-shrink: 0;
    padding-top: 45px;
    padding-bottom: 40px;
    width: 250px;
    color: #cbccd6;
    line-height: 1.4;
  }

  .roulette-list-item:nth-child(odd) {
    background-color: #141627;
  }

  .roulette-list-item:nth-child(even) {
    background-color: #131425;
  }

  .roulette-list-item-wrap {
    position: relative;
    z-index: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-left: auto;
    margin-right: auto;
    width: 190px;
    height: 190px;
    margin-bottom: 5px;
  }

  .roulette-list-item-wrap::before {
    content: '';
    position: absolute;
    z-index: -2;
    top: 50%;
    left: 50%;
    width: 2px;
    height: 2px;
    border-radius: 50%;
  }

  .roulette-list-item-wrap::after {
    content: '';
    position: absolute;
    z-index: -1;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
  }

  .roulette-list-item:nth-child(odd) .roulette-list-item-wrap::after {
    background: linear-gradient(to top, rgba(20, 22, 39, .9), rgba(20, 22, 39, 0) 80%);
  }

  .roulette-list-item:nth-child(even) .roulette-list-item-wrap::after {
    background: linear-gradient(to top, rgba(19, 20, 37, .9), rgba(19, 20, 37, 0) 80%);
  }

  .roulette-list-item-polygon {
    position: absolute;
    z-index: -1;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    stroke-width: 6px;
  }

  .roulette-list-item-polygon path:last-child {
    stroke-width: 3px;
  }

  .roulette-list-item1 {
    .roulette-list-item-wrap::before {
      box-shadow: 0 0 55px 45px #af8123;
      background: rgba(175, 129, 35, .6);
    }

    .roulette-list-item-polygon {
      stroke: #cead48;
    }

    .roulette-list-item-polygon path:last-child {
      stroke: rgba(237, 182, 51, .18);
    }
  }

  .roulette-list-item2 {
    .roulette-list-item-wrap::before {
      box-shadow: 0 0 60px 45px #8c1e00;
      background: rgba(140, 30, 0, .6);
    }

    .roulette-list-item-polygon {
      stroke: #b83232;
    }

    .roulette-list-item-polygon path:last-child {
      stroke: rgba(232, 32, 27, .18);
    }
  }

  .roulette-list-item3 {
    .roulette-list-item-wrap::before {
      box-shadow: 0 0 55px 45px #a6337b;
      background: rgba(166, 51, 123, .6);
    }

    .roulette-list-item-polygon {
      stroke: #bd407b;
    }

    .roulette-list-item-polygon path:last-child {
      stroke: rgba(237, 51, 112, .18);
    }
  }

  .roulette-list-item4 {
    .roulette-list-item-wrap::before {
      box-shadow: 0 0 55px 45px #5835a5;
      background: rgba(88, 53, 165, .6);
    }

    .roulette-list-item-polygon {
      stroke: #711ffb;
    }

    .roulette-list-item-polygon path:last-child {
      stroke: rgba(113, 31, 251, .18);
    }
  }

  .roulette-list-item5 {
    .roulette-list-item-wrap::before {
      box-shadow: 0 0 55px 45px #1c2da5;
      background: rgba(28, 45, 165, .6);
    }

    .roulette-list-item-polygon {
      stroke: #2a2ede;
    }

    .roulette-list-item-polygon path:last-child {
      stroke: rgba(51, 51, 237, .18);
    }
  }

  .roulette-list-item6 {
    .roulette-list-item-wrap::before {
      box-shadow: 0 0 55px 45px #2563a0;
      background: rgba(37, 99, 160, .6);
    }

    .roulette-list-item-polygon {
      stroke: #4093cd;
    }

    .roulette-list-item-polygon path:last-child {
      stroke: rgba(51, 160, 237, .18);
    }
  }

  .roulette-list-item7 {
    .roulette-list-item-wrap::before {
      box-shadow: 0 0 50px 45px #888;
      background: rgba(136, 136, 136, .6);
    }

    .roulette-list-item-polygon {
      stroke: #ddd;
    }

    .roulette-list-item-polygon path:last-child {
      stroke: rgba(221, 221, 221, .18);
    }
  }

  .roulette-list-item-img {
    width: 100%;
    max-height: 100%;
    filter: saturate(150%);
  }

  .roulette-bg {
    position: absolute;
    z-index: -1;
    top: -120px;
    left: 50%;
    width: 886px;
    height: 624px;
    transform: translateX(-50%);
  }

  .roulette-list-item-info {
    padding: 0 5px;
    white-space: nowrap;
  }

  .roulette-list-item-name {
    overflow: hidden;
    text-overflow: ellipsis;
    font-weight: 700;
    font-size: 14px;
  }

  .roulette-decor {
    position: absolute;
    z-index: 1;
    top: 0;
    left: 50%;
    height: 100%;
    width: 394px;
    transform: translateX(-50%);
    border-bottom: 1px solid #6546d0;
    pointer-events: none;
  }

  .roulette-decor::before {
    top: 0;
    border-left: 9px solid transparent;
    border-right: 9px solid transparent;
    border-top: 16px solid #6546d0;
    content: '';
    position: absolute;
    z-index: 1;
    left: 50%;
    transform: translateX(-50%);
  }

  .roulette-decor::after {
    bottom: 0;
    border-left: 9px solid transparent;
    border-right: 9px solid transparent;
    border-bottom: 16px solid #6546d0;
    content: '';
    position: absolute;
    z-index: 1;
    left: 50%;
    transform: translateX(-50%);
  }

  .roulette2 {
    .roulette-item + .roulette-item {
      margin-top: 15px;
    }
  }

  .roulette3 {
    .roulette-item + .roulette-item {
      margin-top: 5px;
    }

    .roulette-item {
      -webkit-clip-path: polygon(0 50px, 50px 0, calc(100% - 50px) 0, 100% 50px, 100% calc(100% - 50px), calc(100% - 50px) 100%, 50px 100%, 0 calc(100% - 50px));
      clip-path: polygon(0 50px, 50px 0, calc(100% - 50px) 0, 100% 50px, 100% calc(100% - 50px), calc(100% - 50px) 100%, 50px 100%, 0 calc(100% - 50px));
    }

    .roulette-list-item {
      width: 200px;
      padding-top: 25px;
      padding-bottom: 20px;
    }

    .roulette-list-item-wrap {
      width: 110px;
      height: 110px;
    }

    .roulette-list-item1 {
      .roulette-list-item-wrap::before {
        box-shadow: 0 0 40px 24px #af8123;
      }
    }

    .roulette-list-item2 {
      .roulette-list-item-wrap::before {
        box-shadow: 0 0 40px 24px #8c1e00;
      }
    }

    .roulette-list-item3 {
      .roulette-list-item-wrap::before {
        box-shadow: 0 0 40px 24px #a6337b;
      }
    }

    .roulette-list-item4 {
      .roulette-list-item-wrap::before {
        box-shadow: 0 0 40px 24px #5835a5;
      }
    }

    .roulette-list-item5 {
      .roulette-list-item-wrap::before {
        box-shadow: 0 0 40px 24px #1c2da5;
      }
    }

    .roulette-list-item6 {
      .roulette-list-item-wrap::before {
        box-shadow: 0 0 40px 24px #2563a0;
      }
    }

    .roulette-list-item7 {
      .roulette-list-item-wrap::before {
        box-shadow: 0 0 40px 24px #888;
      }
    }
  }

  .roulette4 {
    .roulette-item + .roulette-item {
      margin-top: 5px;
    }

    .roulette-item {
      -webkit-clip-path: polygon(0 40px, 40px 0, calc(100% - 40px) 0, 100% 40px, 100% calc(100% - 40px), calc(100% - 40px) 100%, 40px 100%, 0 calc(100% - 40px));
      clip-path: polygon(0 40px, 40px 0, calc(100% - 40px) 0, 100% 40px, 100% calc(100% - 40px), calc(100% - 40px) 100%, 40px 100%, 0 calc(100% - 40px));
    }

    .roulette-list-item {
      width: 180px;
      padding-top: 20px;
      padding-bottom: 12px;
    }

    .roulette-list-item-wrap {
      width: 100px;
      height: 100px;
      margin-bottom: 3px;
    }

    .roulette-list-item1 {
      .roulette-list-item-wrap::before {
        box-shadow: 0 0 34px 22px #af8123;
      }
    }

    .roulette-list-item2 {
      .roulette-list-item-wrap::before {
        box-shadow: 0 0 34px 22px #8c1e00;
      }
    }

    .roulette-list-item3 {
      .roulette-list-item-wrap::before {
        box-shadow: 0 0 34px 22px #a6337b;
      }
    }

    .roulette-list-item4 {
      .roulette-list-item-wrap::before {
        box-shadow: 0 0 34px 22px #5835a5;
      }
    }

    .roulette-list-item5 {
      .roulette-list-item-wrap::before {
        box-shadow: 0 0 34px 22px #1c2da5;
      }
    }

    .roulette-list-item6 {
      .roulette-list-item-wrap::before {
        box-shadow: 0 0 34px 22px #2563a0;
      }
    }

    .roulette-list-item7 {
      .roulette-list-item-wrap::before {
        box-shadow: 0 0 34px 22px #888;
      }
    }

    .roulette-decor::before {
      border-width: 10px 6px 0;
    }

    .roulette-decor::after {
      border-width: 0 6px 10px;
    }
  }

  .roulette5 {
    .roulette-item + .roulette-item {
      margin-top: 2px;
    }

    .roulette-item {
      -webkit-clip-path: polygon(0 30px, 30px 0, calc(100% - 30px) 0, 100% 30px, 100% calc(100% - 30px), calc(100% - 30px) 100%, 30px 100%, 0 calc(100% - 30px));
      clip-path: polygon(0 30px, 30px 0, calc(100% - 30px) 0, 100% 30px, 100% calc(100% - 30px), calc(100% - 30px) 100%, 30px 100%, 0 calc(100% - 30px));
    }

    .roulette-list-item {
      width: 160px;
      padding-top: 20px;
      padding-bottom: 10px;
    }

    .roulette-list-item-wrap {
      width: 76px;
      height: 76px;
      margin-bottom: 2px;
    }

    .roulette-list-item1 {
      .roulette-list-item-wrap::before {
        box-shadow: 0 0 26px 18px #af8123;
      }
    }

    .roulette-list-item2 {
      .roulette-list-item-wrap::before {
        box-shadow: 0 0 26px 18px #8c1e00;
      }
    }

    .roulette-list-item3 {
      .roulette-list-item-wrap::before {
        box-shadow: 0 0 26px 18px #a6337b;
      }
    }

    .roulette-list-item4 {
      .roulette-list-item-wrap::before {
        box-shadow: 0 0 26px 18px #5835a5;
      }
    }

    .roulette-list-item5 {
      .roulette-list-item-wrap::before {
        box-shadow: 0 0 26px 18px #1c2da5;
      }
    }

    .roulette-list-item6 {
      .roulette-list-item-wrap::before {
        box-shadow: 0 0 26px 18px #2563a0;
      }
    }

    .roulette-list-item7 {
      .roulette-list-item-wrap::before {
        box-shadow: 0 0 26px 18px #888;
      }
    }

    .roulette-decor::before {
      border-width: 10px 6px 0;
    }

    .roulette-decor::after {
      border-width: 0 6px 10px;
    }
  }

  .case-loader {
    font-weight: 700;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
    color: #a6a4b5;
    padding-top: 3px;
    margin-top: 50px;
    margin-bottom: 46px;

    img {
      position: relative;
      top: -3px;
      margin-left: 20px;
      animation: loading 1s linear infinite;
    }
  }

  @keyframes loading {
    to {
      transform: rotate(360deg);
    }
  }

  @-webkit-keyframes loading {
    to {
      transform: rotate(360deg);
    }
  }

  @media (min-width: 480px) {
  }

  @media (min-width: 768px) {
    .roulette-item {

      -webkit-clip-path: polygon(0 60px, 85px 0, calc(100% - 85px) 0, 100% 60px, 100% calc(100% - 60px), calc(100% - 85px) 100%, 85px 100%, 0 calc(100% - 60px));
      clip-path: polygon(0 60px, 85px 0, calc(100% - 85px) 0, 100% 60px, 100% calc(100% - 60px), calc(100% - 85px) 100%, 85px 100%, 0 calc(100% - 60px));
    }

    .roulette-item::before {
      content: '';
      position: absolute;
      z-index: 1;
      top: 0;
      bottom: 0;
      width: 200px;
    }

    .roulette-item::after {
      content: '';
      position: absolute;
      z-index: 1;
      top: 0;
      bottom: 0;
      width: 200px;
    }

    .roulette-bg {
      background: url(~@/assets/img/roulette-bg.png) center/contain no-repeat;
    }
  }

  .result-info {
    height: 270px;
    margin-bottom: 40px;
    position: relative;
    z-index: -1;

    &::before {
      content: '';
      position: absolute;
      z-index: -1;
      left: 50%;
      top: 50%;
      width: 2px;
      height: 2px;
      border-radius: 50%;
      transform: translate(-50%, -50%);
      box-shadow: 0 0 150px 100px #ddd;
    }

    &::after {
      content: '';
      position: absolute;
      z-index: -1;
      left: 50%;
    }
  }

  .result-item {
    max-width: 350px;
    transform: scale(0);
    animation: scale-up 1s forwards ease-out;
  }

  @keyframes scale-up {
    to {
      transform: scale(1);
    }
  }

  @-webkit-keyframes scale-up {
    to {
      transform: scale(1);
    }
  }

  .result-bg {
    position: absolute;
    z-index: -2;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 624px;
    height: 422px;
    fill: #ddd;
  }

  .result-polygon {
    position: absolute;
    z-index: -1;
    top: 46%;
    left: 50%;
    transform: translate(-50%, -50%) scale(1);
    width: 266px;
    height: 266px;
    stroke-width: 4px;
    -webkit-animation: polygon-scale 1s forwards ease-out;
    animation: polygon-scale 1s forwards ease-out;
    stroke: #ddd;
  }

  .result-polygon-lg {
    width: 360px;
    height: 360px;
    stroke-width: 2px;
    -webkit-animation-duration: 1.2s;
    animation-duration: 1.2s;
    stroke: rgba(221, 221, 221, .18);
  }

  .result-light {
    background-image: url("~@/assets/img/case-light-white.png");
    position: absolute;
    z-index: -1;
    top: 46%;
    left: 50%;
    width: 440px;
    height: 456px;
    background-size: contain;
    -webkit-animation: spin 20s linear infinite;
    animation: spin 20s linear infinite;
  }

  .result-title {
    text-transform: capitalize;
    font-size: 18px;
    color: #eff0f5;
    font-weight: 500;
    line-height: 1.2;
    margin-bottom: 3px;
  }

  .result-name {
    color: #eff0f5;
    font-size: 21px;
    font-weight: 700;
    margin-bottom: 4px;
    line-height: 1.2;
  }

  .result-abrasion {
    color: #767786;
    font-size: 16px;
  }

  .result-buttons {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    margin: 45px 0 27px;
  }

  .result-btn {
    background-color: #3a34b0;
    align-items: center;
    color: #fff;
    display: flex;
    justify-content: center;
    max-width: 316px;
    border-radius: 4px;
    font-size: 17px;
    font-weight: 700;
    margin: 0 9px 18px;
    text-transform: none;
    padding-left: 25px;
    padding-right: 25px;
    width: auto;
    height: 60px;
    padding-top: 2px;
    border: 0;
  }

  .result-btn2 {
    background-color: #7d51df;
  }

  @keyframes spin {
    from {
      transform: translate(-50%, -50%) rotate(0)
    }
    to {
      transform: translate(-50%, -50%) rotate(360deg)
    }
  }

  @-webkit-keyframes spin {
    from {
      transform: translate(-50%, -50%) rotate(0)
    }
    to {
      transform: translate(-50%, -50%) rotate(360deg)
    }
  }

  .result-dialog1 {
    .result-info::before {
      box-shadow: 0 0 150px 100px #4b371a;
    }

    .result-bg {
      fill: #4b371a;
    }

    .result-polygon {
      stroke: #b8a332;
    }

    .result-polygon-lg {
      stroke: rgba(232, 201, 27, .18);
    }

    .result-light {
      background-image: url("~@/assets/img/case-light-yellow.png");
    }
  }

  .result-dialog2 {
    .result-info::before {
      box-shadow: 0 0 150px 100px #4d222d;
    }

    .result-bg {
      fill: #4d222d;
    }

    .result-polygon {
      stroke: #B83232;
    }

    .result-polygon-lg {
      stroke: rgba(232, 32, 27, .18);
    }

    .result-light {
      background-image: url("~@/assets/img/case-light-red.png");
    }
  }

  .result-dialog3 {
    .result-info::before {
      box-shadow: 0 0 150px 100px #4d2246;
    }

    .result-bg {
      fill: #4d2246;
    }

    .result-polygon {
      stroke: #b83274;
    }

    .result-polygon-lg {
      stroke: rgba(232, 27, 138, .18);
    }

    .result-light {
      background-image: url("~@/assets/img/case-light-pink.png");
    }
  }

  .result-dialog4 {
    .result-info::before {
      box-shadow: 0 0 150px 100px #32216f;
    }

    .result-bg {
      fill: #32216f;
    }

    .result-polygon {
      stroke: #4c2ece;
    }

    .result-polygon-lg {
      stroke: rgba(71, 27, 232, .48);
    }

    .result-light {
      background-image: url("~@/assets/img/case-light-violet.png");
    }
  }

  .result-dialog5 {
    .result-info::before {
      box-shadow: 0 0 150px 100px #1c2c67;
    }

    .result-bg {
      fill: #1c2c67;
    }

    .result-polygon {
      stroke: #383fda;
    }

    .result-polygon-lg {
      stroke: rgba(46, 60, 232, .31);
    }

    .result-light {
      background-image: url("~@/assets/img/case-light-blue.png");
    }
  }

  .result-dialog6 {
    .result-info::before {
      box-shadow: 0 0 150px 100px #1b4763;
    }

    .result-bg {
      fill: #1b4763;
    }

    .result-polygon {
      stroke: rgba(50, 116, 184, .69);
    }

    .result-polygon-lg {
      stroke: rgba(27, 157, 232, .14);
    }

    .result-light {
      background-image: url("~@/assets/img/case-light-blue-light.png");
    }
  }

  .result-dialog7 {
    .result-info::before {
      box-shadow: 0 0 150px 100px #ddd;
    }

    .result-bg {
      fill: #ddd;
    }

    .result-polygon {
      stroke: #ddd;
    }

    .result-polygon-lg {
      stroke: rgba(221, 221, 221, .18);
    }

    .result-light {
      background-image: url("~@/assets/img/case-light-white.png");
    }
  }

  .result-dialogs {
    .result-info {
      display: flex;
      flex-wrap: wrap;
      margin-bottom: -1px;
      background: #15162b;
      height: auto;
      z-index: auto;

      &::before {
        content: none;
      }

      &::after {
        content: none;
      }
    }

    .result-buttons {
      border-top: 1px solid #1e2138;
      background-color: #15172b;
      margin: 0;
      padding: 30px 30px 10px;
    }

    .header-main-title {
      font-size: 27px;
      line-height: 1.2;
      color: #fff;
      font-weight: 500;
      text-transform: capitalize;
    }

    .header-sub-title {
      color: #9093a7;

      .price {
        color: #6ecf96;
      }
    }

    .result-btn-disabled {
      opacity: .3;
      cursor: not-allowed;
    }

    .live-item {
      display: flex;
      flex-direction: column;
      flex: 0 0 33.33333%;
      max-width: 33.33333%;
      border: 1px solid #1e2138;
      background-color: #15172c;
      text-align: left;
      padding: 20px;
      position: relative;
      cursor: pointer;

      &:hover {
        border: 1px solid #2d2d67 !important;
        box-shadow: inset 0 0 59px rgb(41 34 139 / 15%);
      }

      &.active {
        border: 1px solid #2d2d67 !important;
        box-shadow: inset 0 0 59px rgb(41 34 139 / 15%);
      }
    }

    .result-btn {
      font-size: 15px;
      font-weight: 500;
      height: 46px;
    }

    .live-item-check {
      display: flex;
      align-items: center;
      justify-content: center;
      position: absolute;
      top: -1px;
      left: -1px;
      width: 20px;
      height: 20px;
      background-color: #2d2d67;
      color: #a6a6cf;
    }

    .icon-check {
      width: 10px;
      height: 8px;
    }

    .live-item-probability {
      position: absolute;
      top: -1px;
      left: -1px;
      right: -1px;
      padding: 5px 10px;
      background-color: rgba(48, 52, 88, .23);
      font-size: 10px;
      color: #a1a3b6;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .live-item-price {
      font-size: 13px;
      font-weight: 700;
      color: #7bdca2;
      margin-bottom: 3px;
    }

    .live-item-price-boundary {
      margin: 0 5px;
    }

    .live-item-img {
      width: 100%;
      max-width: 120px;
      margin-left: auto;
      margin-right: auto;
      margin-bottom: -10px;
    }

    .o-bg {
      width: 88px;
      height: 108px;
    }
  }

  @media (min-width: 992px) {
    .live-item-chance-table {
      td {
        white-space: nowrap;
      }
    }
  }

  @media (min-width: 1200px) {
  }

  @media (min-width: 1440px) {
  }

  @media only screen and (max-width: 1439px) {
  }

  @media only screen and (max-width: 1199px) {
    .live-item {
      flex-basis: 20%;
      max-width: 20%;
    }
  }

  @media only screen and (max-width: 991px) {
    .live-item {
      flex-basis: 25%;
      max-width: 25%;
    }
  }

  @media only screen and (max-width: 767px) {
    .case-product {
      height: 210px;
    }

    .case-img {
      width: 260px !important;
    }

    .case-tab-item:not(:last-child) {
      margin-right: 15px;
    }

    .case-btn {
      max-width: 276px;
    }

    .live-item {
      flex-basis: 50%;
      max-width: 50%;
    }

    .case-inner {
      padding: 0;
    }

    .result-dialogs {
      .live-item {
        flex-basis: 50%;
        max-width: 50%;
      }
    }
  }

  @media only screen and (max-width: 479px) {
  }
</style>
