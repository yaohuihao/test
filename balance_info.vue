<template>
  <div>
    <template v-if="loadingIndex">
      <div class="text-center">
        <i class="el-icon-loading data-loading"></i>
      </div>
    </template>
    <template v-else>
      <div class="account-inner">
        <div class="account-header">
          <div class="account-user">
            <div class="account-avatar-wrap">
              <el-upload
                class="avatar-uploader"
                :action="server_url + '/api.php/user/portrait_set'"
                :headers="{secret:secret,authorization: `Basic ${jwt_token}`}"
                :show-file-list="false"
                :before-upload="avatar_upload"
                :on-success="avatar_success">
                <img class="account-avatar" v-if="user.portrait" :src="repair(user.portrait)" alt="">
                <i v-else class="el-icon-plus avatar-uploader-icon"></i>
              </el-upload>
            </div>
            <div class="account-info">
              <div class="account-name">
                {{ user.name }}
                UID : {{ user.uid }}
              </div>
              <div class="account-balance">
                用户余额 : <span>$ {{ user.bean }}</span>

                <!--                <button class="account-add-balance">-->
                <!--                  <svg class="icon-plus-circle" viewBox="0 0 32.81 32.688">-->
                <!--                    <path-->
                <!--                      d="M3408.93,69.341a16.415,16.415,0,1,0,16.42-16.35A16.388,16.388,0,0,0,3408.93,69.341Zm18.57-6.383v4.7h4.74a1.684,1.684,0,0,1,0,3.367h-4.74v4.72a1.675,1.675,0,0,1-1.73,1.625,1.612,1.612,0,0,1-1.65-1.625v-4.72h-4.71a1.684,1.684,0,1,1,0-3.367h4.71v-4.7A1.69,1.69,0,0,1,3427.5,62.958Z"-->
                <!--                      transform="translate(-3408.94 -53)"/>-->
                <!--                  </svg>-->
                <!--                </button>-->
              </div>
              <div class="account-balance" v-if="user.plan_bean != 0">
                锁定余额 : <span>$ {{ user.plan_bean }}</span>
              </div>
            </div>

            <div class="account-user-right">
<!--              <button class="account-edit-btn">
                <span>每日存入上限 : {{superior_bean}}</span>
              </button>
              <button class="account-edit-btn">
                <span>今日已存入 : {{plan_beans || 0}}</span>
              </button>-->
              <button class="account-edit-btn" @click="link_edit">
                <svg class="icon-pencil" viewBox="0 0 32 32">
                  <g>
                    <g>
                      <path
                        d="M31.295 4.818c.454.453.704 1.056.704 1.698 0 .642-.25 1.245-.704 1.698l-21.95 21.95a.815.815 0 0 1-.41.218L.93 31.983a.801.801 0 0 1-.942-.942l1.602-8.006a.792.792 0 0 1 .218-.41L23.757.678a2.403 2.403 0 0 1 3.398 0zm-5.136.995a.8.8 0 0 0-1.132 0L7.412 23.427a.8.8 0 1 0 1.132 1.132L26.159 6.945a.8.8 0 0 0 0-1.132z"/>
                    </g>
                  </g>
                </svg>
                <span>CDK补贴计划</span>
              </button>
            </div>
          </div>
        </div>
        <div class="account-content">
          <div class="account-tabs">
            <div class="account-tabs-buttons">
              <div :class="'account-tabs-btn ' + [type == '' || type == 'all' ? 'active' : '']"
                   @click="type_switch('')">
                <svg class="icon icon-user" viewBox="0 0 30 34">
                  <path
                    d="M29.997 29.978v2.009a1.993 1.993 0 0 1-2.02 2.008H2.015a1.993 1.993 0 0 1-2.02-2.008v-1.972c0-2.98 1.18-5.795 3.324-7.926a11.523 11.523 0 0 1 3.754-2.47 13.312 13.312 0 0 1-.163-.16c-2.167-2.155-3.361-5.01-3.361-8.038 0-3.029 1.194-5.883 3.361-8.039C9.016 1.207 11.891.004 14.996.004c3.047 0 5.918 1.186 8.086 3.341 2.167 2.155 3.361 5.01 3.361 8.038 0 3.026-1.227 5.958-3.366 8.044a9.324 9.324 0 0 1-.16.154 11.512 11.512 0 0 1 3.756 2.47c2.112 2.1 3.324 4.99 3.324 7.927zm-23.862-5.09a7.184 7.184 0 0 0-2.137 5.127h21.959a7.183 7.183 0 0 0-2.138-5.127c-1.326-1.352-3.19-2.124-5.12-2.124h-7.406a7.27 7.27 0 0 0-5.158 2.125zM9.727 6.146a7.334 7.334 0 0 0-2.175 5.238c0 1.948.775 3.811 2.181 5.246a7.416 7.416 0 0 0 5.263 2.155c1.96 0 3.833-.77 5.276-2.168a7.33 7.33 0 0 0 2.169-5.233c0-1.946-.774-3.807-2.179-5.241a7.422 7.422 0 0 0-5.266-2.16 7.422 7.422 0 0 0-5.27 2.163z"/>
                </svg>
                <span>cdk领取历史</span>
              </div>
              <div :class="'account-tabs-btn ' + [type == 'recharge_record' ? 'active' : '']"
                   @click="type_switch('recharge_record')">
                <svg class="icon icon-enter" viewBox="0 0 44 34">
                  <g>
                    <g>
                      <path
                        d="M39.524 34H13.238a4.535 4.535 0 0 1-3.163-1.288 4.35 4.35 0 0 1-1.313-3.117v-2.87H4.475c-.473 0-.932-.19-1.26-.52a1.72 1.72 0 0 1 .009-2.475 1.772 1.772 0 0 1 1.251-.513h4.287v-4.464H1.779c-.473 0-.932-.19-1.26-.52a1.72 1.72 0 0 1 .009-2.475 1.772 1.772 0 0 1 1.251-.512h6.983v-4.464H4.475c-.473 0-.932-.19-1.259-.521a1.72 1.72 0 0 1 .008-2.475 1.773 1.773 0 0 1 1.251-.512h4.287v-2.87c0-1.176.466-2.283 1.313-3.117a4.535 4.535 0 0 1 3.163-1.288h26.286c1.18 0 2.333.47 3.163 1.288A4.349 4.349 0 0 1 44 4.404v25.19a4.35 4.35 0 0 1-1.313 3.118A4.535 4.535 0 0 1 39.524 34zm-27.203-7.275v2.87a.89.89 0 0 0 .271.636c.168.166.404.26.646.26h26.286a.926.926 0 0 0 .646-.26.89.89 0 0 0 .271-.636V4.405a.89.89 0 0 0-.271-.637.925.925 0 0 0-.646-.26H13.238a.925.925 0 0 0-.646.26.89.89 0 0 0-.271.636v2.87h4.96c.474 0 .933.19 1.26.52a1.722 1.722 0 0 1-.008 2.475 1.773 1.773 0 0 1-1.251.513H12.32v4.464h15.92l-1.527-1.756a1.731 1.731 0 0 1-.42-1.272c.036-.464.253-.889.612-1.198a1.794 1.794 0 0 1 1.293-.417c.47.035.902.25 1.216.603l4.047 4.653c.277.32.426.727.421 1.145.005.41-.144.815-.42 1.135l-4.045 4.651c-.317.357-.748.571-1.219.606a1.787 1.787 0 0 1-1.289-.413 1.755 1.755 0 0 1-.615-1.201 1.731 1.731 0 0 1 .418-1.271l1.528-1.758h-15.92v4.464h4.96c.474 0 .933.19 1.26.521a1.72 1.72 0 0 1-.008 2.475 1.771 1.771 0 0 1-1.251.512z"/>
                    </g>
                  </g>
                </svg>
                <span>锁定记录</span>
              </div>
            </div>
          </div>
        </div>
        <div v-if="type == '' || type == 'all'" class="account-items">
          <label class="account-toggle" style="color:#ffffff;">
            请在 {{day_config || 0}} 天内领取CDK
          </label>
          <table class="level-table">
            <colgroup>
              <col width="25%">
              <col width="25%">
              <col width="25%">
            </colgroup>
            <tbody>

            <tr class="level-table-header">
              <th class="level-table-col-title">cdk名称</th>
              <th class="level-table-col-title">cdk信息</th>
              <th class="level-table-col-title">金额</th>
              <th class="level-table-col-title">已发放</th>
              <th class="level-table-col-title">状态</th>
            </tr>
            <tr class="level-table-row2" v-for="(item, index) in cdk_record">
              <td class="level-table-cell">
                <span> {{ item.name }}</span>
              </td>
              <td class="level-table-cell">
                <span> {{ item.cdk }}</span>
              </td>
              <td class="level-table-cell">
                <span>{{ item.num }}</span>
              </td>
              <td class="level-table-cell">
                <span>{{ item.issued }}</span>
              </td>
              <td class="level-table-cell">
                <span>{{ item.status == 0 ? "正常" : "已失效" }}</span>
              </td>
            </tr>
            </tbody>
          </table>
          <div class="more" v-if="page3_status" @click="more3">
            <i class="el-icon-bottom"></i>
          </div>
        </div>
        <div v-if="type == 'recharge_record'">
          <table class="level-table">
            <colgroup>
              <col width="15%">
              <col width="18%">
              <col width="15%">
              <col width="17%">
              <col width="17%">
              <col width="17%">
            </colgroup>
            <tbody>
            <tr class="level-table-header">
              <th class="level-table-col-title">转入金额</th>
              <th class="level-table-col-title">转入时间</th>
              <th class="level-table-col-title">转入天数</th>
              <th class="level-table-col-title">奖励</th>
              <th class="level-table-col-title">时效状态</th>
              <th class="level-table-col-title">审核状态</th>
              <th class="level-table-col-title">领取cdk</th>
            </tr>
            <tr class="level-table-row2" v-for="(item, index) in recharge_record">
              <td class="level-table-cell">
                <span>$ {{ item.plan_bean }}</span>
              </td>
              <td class="level-table-cell">
                <span>{{ item.add_time }}</span>
              </td>
              <td class="level-table-cell">
                <span>{{ item.day_num }}</span>
              </td>
              <td class="level-table-cell">
                <span>{{ item.zong_price }}</span>
              </td>
              <td class="level-table-cell">
                <template v-if="item.status == 0"><span>正常</span></template>
                <template v-else>已失效</template>
              </td>
              <td class="level-table-cell">
                <template v-if="item.is_show == 0"><span>待审核</span></template>
                <template v-else-if="item.is_show == 1">审核成功</template>
                <template v-else>审核失败</template>
              </td>
              <span class="level-table-cell" v-if="item.is_show == 1">
                <td class="level-table-cell" v-if="item.status == 0">
                  <button v-if="item.is_ling == 0" class="live-item-btn" @click="cdk($event,item.id)">领取cdk</button>
                  <button v-else-if="item.is_ling == 3" class="live-item-btn">明日开始领取</button>
                  <button v-else class="live-item-btn">今日已领取</button>
                </td>
                <td class="level-table-cell" v-else>
                  <button class="live-item-btn">已过期</button>
                </td>
              </span>
              <span class="level-table-cell" v-else-if="item.is_show == 0">
                <td class="level-table-cell">
                  <button class="live-item-btn">待审核</button>
                </td>
              </span>
              <span class="level-table-cell" v-else>
                <td class="level-table-cell">
                    <button class="live-item-btn" @click="cdk_del($event,item.id)">删除</button>
                </td>
              </span>
            </tr>
            </tbody>
          </table>
          <div class="more" v-if="page3_status" @click="more3">
            <i class="el-icon-bottom"></i>
          </div>
        </div>
      </div>
    </template>
    <el-dialog
      class="link-dialog"
      title="编辑名称"
      append-to-body
      :visible.sync="name_page"
      :close-on-click-modal="false"
      :lock-scroll="false">
      <div class="link-info">
        <input class="recharge-input" v-model="user.name" placeholder="请输入名称">
        <button class="recharge-btn" @click="name_set">锁定</button>
      </div>
    </el-dialog>
    <el-dialog
      class="link-dialog"
      title="CDK补贴计划"
      append-to-body
      :visible.sync="link_page"
      :close-on-click-modal="false"
      :lock-scroll="false">
      <div class="link-info">

          <span>日锁定上限 : {{superior_bean}}</span>
          <span style="margin-left: 20px">今日已锁定 : {{plan_beans || 0}}</span>

        <el-input class="recharge-input" v-model="plan_bean" placeholder="请输入要转入的金额">
        </el-input>
        <button slot="append" class="recharge-btn2">锁定天数</button>
         <el-row>
           <el-button v-for="(item, index) in rate_plan" type="primary" @click="getData(''+index+'',''+item.day_num+'')" plain>{{item.day_num}}天</el-button>
         </el-row>
        <button slot="append" style="width: 100%;" class="recharge-btn2">奖励总额</button>
        <el-input class="recharge-input" disabled v-model="zong_price" placeholder="金额">
        </el-input>
        <el-input class="" disabled hidden v-model="rate" placeholder="利率">
        </el-input>
        <el-input class="" hidden v-model="day_number" placeholder="天数">
        </el-input>
        <el-input class="" hidden v-model="rate_id" placeholder="id">
        </el-input>
        <button class="recharge-btn" @click="link_balance">锁定</button>
        <div style="margin-top: 20px;"></div>
        <span>1、该cdk补贴计划是为了回馈老用户的一种奖励制度，用户通过自愿锁定商城boy币来每天免费获取cdk余额，并且一旦锁定，在锁定期限内不得解封。</span>
        <div style="margin-top: 10px;"></div>
        <span>2、奖励的cdk只能用于各种boy游戏，通过兑换获得相应csgo游戏皮肤及道具。与存款制度有着极大的不同，请玩家注意。</span>
        <div style="margin-top: 10px;"></div>
        <span>3、参与锁定的boy币最少锁定为100，以倍数锁定，最高不超过5000，锁定后用户每天24小时后即可领取。</span>
        <span
      </div>
    </el-dialog>

  </div>
</template>

<script>
import {ttGet, ttPost, ttWebSocket, abrasion, quality, quality2, color, repair} from "@/utils/util.js";
import website from '@/config/website';
import {getStore, setStore} from "@/utils/store.js";

export default {
  data() {
    return {
      server_url: this.$server_url,
      secret: website.clientSecret,
      jwt_token: getStore({name: 'jwt_token'}),
      img_url: this.$img_url,
      loadingIndex: true,
      loadingIndex2: true,
      user: {},
      vif: '',
      name_page: false,
      rate:0,
      day_number:0,
      rate_id:0,
      link_page: false,
      superior_invitation_code_page: false,
      type: '',
      ornaments: [],
      value1: false,
      page: 1,
      page_status: false,
      extract_history: [],
      page2: 1,
      page2_status: false,
      recharge_record: [],
      cdk_record: [],
      day_config:0,
      page3: 1,
      page3_status: false,
      partner_history: [],
      rate_plan:[],
      page4: 1,
      page4_status: false,
      ti_page: false,
      ti_index: '',
      ti_type: 1,
      plan_beans:0,
      superior_bean:0,
      zong_price:0,
      plan_bean:0,
    }
  },
  mounted() {
    let type = this.$route.params.type;
    this.type = type || '';
    this.get_user();
    this.get_config();
    this.get_plan_rate();
    switch (this.type) {
      case '':
        this.value1 = false;
        this.page3 = 1;
        this.cdk_record = [];
        this.get_cdk_record();
        break;
      case 'all':
        this.value1 = true;
        this.page = 1;
        this.ornaments = [];
        this.get_ornaments();
        break;
      case 'extract_history':
        this.page2 = 1;
        this.extract_history = [];
        this.get_extract_history();
        break;
      case 'recharge_record':
        this.page3 = 1;
        this.recharge_record = [];
        this.get_recharge_record();
        break;
      case 'partner_history':
        this.page4 = 1;
        this.partner_history = [];
        this.get_partner_history();
        break;
    }
  },
  watch: {
    $route(newVal) {
      let type = newVal.params.type;
      this.type = type || '';
      switch (this.type) {
        case '':
          this.value1 = false;
          this.page = 1;
          this.cdk_record = [];
          this.get_cdk_record();
          break;
        case 'all':
          this.value1 = true;
          this.page = 1;
          this.ornaments = [];
          this.get_ornaments();
          break;
        case 'extract_history':
          this.page2 = 1;
          this.extract_history = [];
          this.get_extract_history();
          break;
        case 'recharge_record':
          this.page3 = 1;
          this.recharge_record = [];
          this.get_recharge_record();
          break;
        case 'partner_history':
          this.page4 = 1;
          this.partner_history = [];
          this.get_partner_history();
          break;
      }
    },
  },
  computed: {
    selected_status: function () {
      let selected_status = false;
      let ornaments = this.ornaments;
      for (let x in ornaments) {
        if (ornaments[x].selected) {
          selected_status = true;
          break;
        }
      }
      return selected_status;
    },
    selected_price: function () {
      let selected_price = 0;
      let ornaments = this.ornaments;
      for (let x in ornaments) {
        if (ornaments[x].selected) selected_price += Number(ornaments[x].price);
      }
      return selected_price.toFixed(2);
    },
  },
  methods: {
    abrasion, quality, quality2, color, repair,

    //获取用户信息
    get_user() {
      ttGet("/api.php/user/user_info", '', (res) => {
        this.loadingIndex = false;
        if (res.code == 200) {
          this.user = res.data.user;
        } else {
          this.$message({
            message: res.msg,
            type: 'warning'
          });
        }
      });
    },

    //切换饰品类型
    value1_switch() {
      this.page = 1;
      this.ornaments = [];
      this.get_ornaments();
    },
    //获取利率配置
    get_plan_rate() {
      let value1 = this.value1;
      let page = this.page;
      this.loadingIndex2 = true;
      ttGet("/api.php/user/get_plan_rate", {}, (res) => {
        this.loadingIndex2 = false;
        if (res.code == 200) {
          this.rate_plan = this.rate_plan.concat(res.data.data.data);
        } else {
          this.$message({
            message: res.msg,
            type: 'warning'
          });
        }
      });
    },
    //获取饰品
    get_ornaments() {
      let value1 = this.value1;
      let page = this.page;
      this.loadingIndex2 = true;
      ttGet("/api.php/user/get_ornaments", {value1: value1, page: page}, (res) => {
        this.loadingIndex2 = false;
        if (res.code == 200) {
          let ornaments = res.data.ornaments.map((item) => {
            item.selected = false;
            return item;
          });
          this.ornaments = this.ornaments.concat(ornaments);
          this.page_status = ornaments.length == 50 ? true : false;
        } else {
          this.$message({
            message: res.msg,
            type: 'warning'
          });
        }
      });
    },

    //查看更多
    more() {
      this.page = ++this.page;
      this.page_status = false;
      this.get_ornaments();
    },

    //类型切换
    type_switch(type) {
      this.$router.push({path: '/balance_info' + (type ? '/' + type : '')});
    },

    //饰品选择
    ornaments_switch(index) {
      if (!this.value1 && this.ornaments[index].status == 0) {
        this.ornaments[index].selected = !this.ornaments[index].selected;
      }
    },

    //全选饰品
    select_all() {
      if (this.value1) return;
      let ornaments = this.ornaments;
      for (let x in ornaments) {
        if (ornaments[x].status == 0) {
          ornaments[x].selected = !ornaments[x].selected;
        }
      }
    },

    //存入
    deposit() {
      let ornaments = this.ornaments;
      let record_id = [];
      for (let x in ornaments) {
        if (ornaments[x].selected) record_id.push(ornaments[x].record_id);
      }
      if (record_id.length == 0) return;
      let loading = this.$loading({
        spinner: 'el-icon-loading',
        background: 'rgb(0 0 0 / 0%)'
      });
      ttPost("/api.php/user/deposit", {record_id: record_id}, (res) => {
        loading.close();
        if (res.code == 200) {
          this.page = 1;
          this.ornaments = [];
          this.get_ornaments();
          this.user.bean = Number(res.data.balance).toFixed(2);
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

    ti (index){
      this.ti_page = true;
      this.ti_index = index;
    },
   //领取cdk
    cdk(e,id) {
      let plan_id = id;
      let loading = this.$loading({
        spinner: 'el-icon-loading',
        background: 'rgb(0 0 0 / 0%)'
      });
      ttPost("/api.php/user/plan_cdk", {plan_id: plan_id}, (res) => {
        loading.close();
        if (res.code == 200) {
          this.ti_page = false;
          this.page = 1;
          this.ornaments = [];
          this.get_ornaments();
          this.$message({
            message: res.msg,
            type: 'success'
          });
          this.$router.go(0);
        } else {
          this.$message({
            message: res.msg,
            type: 'warning'
          });
        }
      });
    },
    //删除c审核失败数据
    cdk_del(e,id) {
      let plan_id = id;
      let loading = this.$loading({
        spinner: 'el-icon-loading',
        background: 'rgb(0 0 0 / 0%)'
      });
      ttPost("/api.php/user/plan_cdk_del", {plan_id: plan_id}, (res) => {
        loading.close();
        if (res.code == 200) {
          this.$message({
            message: res.msg,
            type: 'success'
          });
          this.$router.go(0);
        } else {
          this.$message({
            message: res.msg,
            type: 'warning'
          });
        }
      });
    },
    //编辑昵称
    name_edit() {
      this.name_page = true;
    },
    getData(e,num) {
      this.day_number = num
      // 输出 100
      this.superior_bean = this.rate_plan[e].day_bean
      this.rate = this.rate_plan[e].rate;
      this.rate_id = this.rate_plan[e].id;
      let day_num = this.rate_plan[e].day_num;
      let price = this.plan_bean * (this.rate_plan[e].rate/100) * this.rate_plan[e].day_num;
      this.zong_price = parseFloat(price).toFixed(2)
      ttGet("/api.php/user/get_plan_config", {rate_id: this.rate_id}, (res) => {
        if (res.code == 200) {
          this.plan_beans = res.data.recharge_record.plan_beans;
        } else {
          this.$message({
            message: res.msg,
            type: 'warning'
          });
        }
      });

    },
    get_config() {
      ttGet("/api.php/user/get_config", {}, (res) => {
        if (res.code == 200) {
          this.day_config = res.data.site.superior_bean;
        } else {
          this.$message({
            message: res.msg,
            type: 'warning'
          });
        }
      });

    },
    //保存名称
    name_set() {
      let name = this.user.name;
      if (!name) {
        return this.$message({
          message: '请输入名称!',
          type: 'warning'
        });
      }
      let loading = this.$loading({
        spinner: 'el-icon-loading',
        background: 'rgba(0, 0, 0, 0.7)'
      });
      ttPost("/api.php/user/name_set", {name: name}, (res) => {
        loading.close();
        if (res.code == 200) {
          this.user.name = name;
          this.name_page = false;
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

    //编辑交易链接
    link_edit() {
      this.link_page = true;
    },

    //保存交易链接
    link_set() {
      let transaction_link = this.user.transaction_link;
      let transaction_link2 = this.user.transaction_link2;
      let transaction_link3 = this.user.transaction_link3;
      let phone = this.user.phone;
      let vif = this.vif;
      if (!transaction_link) {
        return this.$message({
          message: '请输入交易链接!',
          type: 'warning'
        });
      }
      if (phone && !vif) {
        return this.$message({
          message: '请输入验证码!',
          type: 'warning'
        });
      }
      let loading = this.$loading({
        spinner: 'el-icon-loading',
        background: 'rgba(0, 0, 0, 0.7)'
      });
      ttPost("/api.php/user/link_set", {transaction_link: transaction_link, transaction_link2: transaction_link2, transaction_link3: transaction_link3, vif: vif}, (res) => {
        loading.close();
        if (res.code == 200) {
          this.link_page = false;
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

    //保存计划余额转入
    link_balance() {
      let plan_bean = this.plan_bean;
      let day_num = this.day_number;
      let rate = this.rate;
      let rate_id = this.rate_id;
      let vif = this.vif;
      if (!plan_bean) {
        return this.$message({
          message: '请输入要锁定的金额!',
          type: 'warning'
        });
      }
      if (!day_num) {
        return this.$message({
          message: '请选择要锁定的天数!',
          type: 'warning'
        });
      }
      let loading = this.$loading({
        spinner: 'el-icon-loading',
        background: 'rgba(0, 0, 0, 0.7)'
      });
      ttPost("/api.php/user/balance_set", {plan_bean: plan_bean, day_num: day_num, rate: rate,rate_id:rate_id}, (res) => {
        loading.close();
        if (res.code == 200) {
          this.link_page = false;
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

    //用户注册获取验证码
    get_vif() {
      let loading = this.$loading({
        spinner: 'el-icon-loading',
        background: 'rgba(0, 0, 0, 0.7)'
      });
      ttPost("/api.php/user/get_vif", '', (res) => {
        loading.close();
        if (res.code == 200) {
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

    //上级推广代码
    superior_invitation_code_edit() {
      this.superior_invitation_code_page = true;
    },

    //保存上级推广代码
    superior_invitation_code_set() {
      let superior_invitation_code = this.user.superior_invitation_code;
      if (!superior_invitation_code) {
        return this.$message({
          message: '请输入上级推广代码!',
          type: 'warning'
        });
      }
      let loading = this.$loading({
        spinner: 'el-icon-loading',
        background: 'rgba(0, 0, 0, 0.7)'
      });
      ttPost("/api.php/user/superior_invitation_code_set", {superior_invitation_code: superior_invitation_code}, (res) => {
        loading.close();
        if (res.code == 200) {
          this.superior_invitation_code_page = false;
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

    //获取用户提取历史
    get_extract_history() {
      let page2 = this.page2;
      this.loadingIndex2 = true;
      ttGet("/api.php/user/get_extract_history", {page: page2}, (res) => {
        this.loadingIndex2 = false;
        if (res.code == 200) {
          this.extract_history = this.extract_history.concat(res.data.extract_history);
          this.page2_status = res.data.extract_history.length == 50 ? true : false;
        } else {
          this.$message({
            message: res.msg,
            type: 'warning'
          });
        }
      });
    },

    //查看更多
    more2() {
      this.page2 = ++this.page2;
      this.page2_status = false;
      this.get_extract_history();
    },

    //获取用户锁定记录
    get_recharge_record() {
      let page3 = this.page3;
      this.loadingIndex2 = true;
      ttGet("/api.php/user/get_plan_record", {page: page3}, (res) => {
        this.loadingIndex2 = false;
        if (res.code == 200) {
          this.recharge_record = this.recharge_record.concat(res.data.recharge_record);
          this.page3_status = res.data.recharge_record.length == 50 ? true : false;
        } else {
          this.$message({
            message: res.msg,
            type: 'warning'
          });
        }
      });
    },

    //获取用户cdk领取记录
    get_cdk_record() {
      let page3 = this.page3;
      this.loadingIndex2 = true;
      ttGet("/api.php/user/get_user_cdk_record", {page: page3}, (res) => {
        this.loadingIndex2 = false;
        if (res.code == 200) {
          this.cdk_record = this.cdk_record.concat(res.data.data.data);
          this.page3_status = res.data.data.data.length == 50 ? true : false;
        } else {
          this.$message({
            message: res.msg,
            type: 'warning'
          });
        }
      });
    },

    //查看更多
    more3() {
      this.page3 = ++this.page3;
      this.page3_status = false;
      this.get_recharge_record();
    },

    //获取合作伙伴记录
    get_partner_history() {
      let page4 = this.page4;
      this.loadingIndex2 = true;
      ttGet("/api.php/user/get_partner_history", {page: page4}, (res) => {
        this.loadingIndex2 = false;
        if (res.code == 200) {
          this.partner_history = this.partner_history.concat(res.data.partner_history);
          this.page4_status = res.data.partner_history.length == 50 ? true : false;
        } else {
          this.$message({
            message: res.msg,
            type: 'warning'
          });
        }
      });
    },

    //查看更多
    more4() {
      this.page4 = ++this.page4;
      this.page4_status = false;
      this.get_partner_history();
    },

    //图片上传检查
    avatar_upload(file) {
      const isLt2M = file.size / 1024 / 1024 < 2;
      if (!isLt2M) {
        this.$message.error('上传图片大小不能超过 2MB!');
      }
      return isLt2M;
    },

    //图片上传成功
    avatar_success(res, file) {
      if (res.code == 200) {
        this.user.portrait = res.data.img;
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
    },
  }
}
</script>

<style lang="scss" scoped>
.vue-view {
  margin-bottom: 70px;
}

.account-inner {
  margin-top: 30px;
}

.account-header {
  display: flex;
  align-items: center;
  margin-bottom: 20px;
}

.account-user {
  position: relative;
  display: flex;
  align-items: center;
  margin-bottom: auto;
  flex-wrap: wrap;
  flex-grow: 1;
  z-index: 0;
}

.account-avatar-wrap {
  position: relative;
  flex-shrink: 0;
  margin-right: 15px;
}

.account-avatar {
  border-radius: 22px;
  width: 82px;
  height: 82px;
}

.account-info {
  flex-grow: 1;
  overflow: hidden;
  margin-right: auto;
}

.account-name {
  position: relative;
  white-space: nowrap;
  overflow: hidden;
  color: #edeef3;
  font-size: 23px;
}

.account-balance {
  position: relative;
  white-space: nowrap;
  overflow: hidden;
  font-size: 30px;
  color: #75dc9e;
}

.account-add-balance {
  border: 0;
  padding: 0;
  background-color: transparent;
  cursor: pointer;
  position: relative;
  top: -2px;
  align-self: flex-end;
  font-weight: 500;
  flex-shrink: 0;
  margin-left: 8px;
  color: #63bd87;
  transition: color .15s;
  margin-top: 5px;
}

.icon-plus-circle {
  width: 30px;
  height: 30px;
  vertical-align: middle;
}

.account-user-right {
  display: flex;
}

.account-edit-btn {
  border: 0;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-grow: 1;
  font-size: 16px;
  padding-left: 30px;
  padding-right: 30px;
  height: 60px;
  font-weight: 500;
  color: #d8d9e5;
  white-space: nowrap;
  overflow: hidden;
  background-color: #181a31;
  margin-left: 10px;
  border-radius: 6px;

  &:first-child {
    margin-left: 0;
  }

  &:hover {
    color: #fff;
    background-color: #0a0b16;
    transition: background-color .15s, color .15s;
  }
}

.icon-pencil {
  width: 18px;
  height: 18px;
  flex-shrink: 0;
  position: relative;
  top: -2px;
  color: #6e708e;
  margin-right: 14px;
  transition: color .15s;
  vertical-align: middle;
}

.account-content {
  display: flex;
}

.account-tabs {
  flex-grow: 1;
}

.account-tabs-buttons {
  display: flex;
}

.icon-user {
  width: 16px;
  height: 16px;
  position: relative;
  flex-shrink: 0;
  margin-right: 10px;
  color: #373c5b;
  vertical-align: middle;
}

.icon-history-2 {
  width: 16px;
  height: 20px;
  position: relative;
  flex-shrink: 0;
  margin-right: 10px;
  color: #373c5b;
}

.icon-enter {
  width: 22px;
  height: 16px;
  position: relative;
  flex-shrink: 0;
  margin-right: 10px;
  color: #373c5b;
}

.icon-users {
  width: 22px;
  height: 16px;
  position: relative;
  flex-shrink: 0;
  margin-right: 10px;
  color: #373c5b;
}

.icon-cancel {
  width: 18px;
  height: 18px;
  position: relative;
  flex-shrink: 0;
  margin-right: 10px;
  color: #373c5b;
}

.account-tabs-btn {
  border: 0;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  white-space: nowrap;
  flex-grow: 1;
  height: 80px;
  padding-top: 3px;
  font-size: 16px;
  color: #9ca1c7;
  font-weight: 500;
  text-align: left;
  background-color: rgba(33, 33, 42, .74);
  border-right: 1px solid #15172c;
  border-bottom: 1px solid transparent;
  transition: color .15s, border-color .15s;
  min-width: 120px;

  &:hover {
    color: #e59d5d;
    border-bottom-color: #e59d5d;

    .icon {
      color: #e59d5d;
    }
  }

  &.active {
    color: #e59d5d;
    border-bottom-color: #e59d5d;
    border-right: 0;

    .icon {
      color: #e59d5d;
    }
  }

  &.active:hover {
    color: #e59d5d;
    border-bottom-color: #e59d5d;

    .icon {
      color: #e59d5d;
    }
  }
}

.account-pro {
  display: flex;
  align-items: center;
  color: #fff;
  font-size: 16px;
  line-height: 1;
  padding: 13px 12px 12px 16px;
  background-image: linear-gradient(-111deg, #5c0feb 0, #ce40ed 100%);
  flex-grow: 1;
  font-weight: 700;
  white-space: nowrap;
  border-radius: 0 3px 3px 0;
  justify-content: center;
}

.account-items {
  padding-top: 40px;
}

.account-items-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 40px;
}

.account-items-title {
  position: relative;
  z-index: 0;
  margin: 0;
  color: #fff;
  font-size: 24px;
  text-transform: uppercase;
  font-weight: 500;
}

.account-items-title::before {
  width: 196px;
  height: 106px;
  background: url("~@/assets/img/heading-bg.svg") center/contain no-repeat;
  transform: translate(-55%, -55%);
  position: absolute;
  z-index: -1;
  top: 50%;
  left: 50%;
  content: '';
}

.account-items-title::after {
  transform: translate(-50%, -50%);
  width: 100px;
  height: 2px;
  border-radius: 50%;
  background-color: rgba(58, 62, 95, .5);
  box-shadow: 0 0 30px 10px #3a3e5f;
  position: absolute;
  z-index: -1;
  top: 50%;
  left: 50%;
  content: '';
}

.account-toggle {
  > > > {
    .el-switch__label.is-active {
      color: #2ae1bc;
    }

    .el-switch__label--right {
      color: #5c5e79;
      margin-left: 15px;
    }

    .el-switch__label--left {
      color: #5c5e79;
      margin-right: 15px;
    }

    .el-switch__core {
      background-color: #1e213b !important;
      border: 0;
      width: 46px !important;
      height: 26px;
      border-radius: 17px;
    }

    .el-switch__core::after {
      background-color: #bbb;
      left: 5px;
      top: 5px;
    }

    .el-switch.is-checked .el-switch__core::after {
      left: 90%;
      background-color: #5ac8ce;
    }
  }
}

.account-items-buttons {
  display: flex;
}

.account-items-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  line-height: 1.2;
  padding-top: 3px;
  height: 45px;
  padding-left: 20px;
  padding-right: 20px;
  background-color: #181a31;
  font-size: 16px;
  font-weight: 700;
  white-space: nowrap;
  transition: color .15s, background-color .15s;
  border-radius: 10px;
  border: 1px solid #2e2925;
  color: #7d51df;

  &.disabled {
    color: #606060;
  }

  &:hover:not(.disabled) {
    border: 1px solid #7d51df;
    color: #7d51df;
  }

  &:first-child {
    color: #7d51df;
  }

  &:not(:last-child) {
    margin-right: 10px;
  }
}

.account-items-items {
  display: flex;
  flex-wrap: wrap;
  border-left: 1px solid #1e2138;
  margin-bottom: 60px;
}

.live-item {
  display: flex;
  flex-direction: column;
  flex: 0 0 14.28571%;
  max-width: 14.28571%;
  border: 1px solid #1e2138;
  border-left-color: transparent;
  /*border-top-color: transparent;*/
  background-color: #15172c;
  text-align: left;
  padding: 20px 20px 60px;
  position: relative;

  &.active {
    border: 1px solid #2d2d67 !important;
    box-shadow: inset 0 0 59px rgb(41 34 139 / 15%);
  }
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

.live-item-quality {
  margin-top: -13px;
  margin-bottom: 5px;
  margin-left: 0;
  font-size: 8px;
  color: #f9a328;
  white-space: nowrap;
  line-height: 1;
  z-index: 2;
}

.live-item-price {
  font-size: 13px;
  font-weight: 700;
  color: #7bdca2;
  margin-bottom: 3px;
}

.live-item-img {
  width: 100%;
  max-width: 120px;
  margin-left: auto;
  margin-right: auto;
  margin-bottom: -10px;
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

.o-item {
  padding: 0;
}

.live-item-chance {
  font-size: 7px;
  color: #898ece;
  line-height: 1.1;
  position: absolute;
  right: 15px;
  bottom: 76px;

  span {
    display: block;
    font-size: 10px;
    font-weight: 700;
  }
}

.live-item-buttons {
  display: flex;
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
}

.live-item-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 46px;
  font-size: 13px;
  color: #a4a6c5;
  flex-grow: 1;
  background-color: #181a31;
  z-index: 3;
  border: 0;

  &:not(:last-of-type) {
    border-right: 1px solid #15172c;
  }

  &:hover:not(.disabled):not(.status) {
    color: #d5d6e7;
    background-color: #1d203a;
  }

  &.disabled {
    cursor: not-allowed;
  }
}

.more {
  margin: 0 auto;
  text-align: center;
  width: 70px;
  height: 70px;
  line-height: 70px;
  position: relative;
  top: 50%;
  transform: translateY(-50%);
  background: #101120;
  border-radius: 50%;
  cursor: pointer;
}

.el-icon-bottom {
  color: #fff;
  font-size: 50px;
  vertical-align: middle;
}

.link-dialog {
  > > > {
    .el-dialog {
      max-width: 560px;
      padding: 50px 70px 55px;
      margin: 0 auto;
      position: relative;
      background-color: #191c34;
    }

    .el-dialog__header {
      padding: 0;
      margin-bottom: 25px;
    }

    .el-dialog__title {
      font-size: 21px;
      font-weight: 500;
      margin: 0;
      color: #fff;
    }

    .el-dialog__body {
      padding: 0;
    }

    .recharge-input {
      width: 100%;
      padding-top: 10px;
      padding-bottom: 13px;
      font-size: 17px;
      border: 0;
      outline: 0;
      color: #fff;
      font-weight: 300;
      border-bottom: 1px solid #3a34b0;
      background-color: transparent;
      box-shadow: none;

      input {
        background-color: transparent;
        border: none;
        color: #FFFFFF;
        padding: 0;
      }

      .el-input-group__append {
        background-color: transparent;
        display: table-cell;
        padding: 0;
        border: none;
        vertical-align: initial;
        border-radius: 0;
      }
    }

    .recharge-btn {
      background-color: #3a34b0;
      align-items: center;
      color: #fff;
      display: inline-block;
      justify-content: center;
      max-width: 316px;
      border-radius: 4px;
      font-size: 17px;
      font-weight: 700;
      margin-top: 20px;
      text-transform: none;
      padding-left: 25px;
      padding-right: 25px;
      width: auto;
      height: 40px;
      line-height: 40px;
      padding-top: 2px;
      border: 0;
    }

    .recharge-btn2 {
      background-color: #191c34;
      color: #61f4ab;
      width: 100px;
      font-size: 14px;
      padding: 0;
      margin-top: 20px;
      margin-bottom: 20px;
      text-align: left;
    }
  }
}

.level-table {
  width: 100%;
  max-width: 100%;
  border-spacing: 0px 2px;
  color: rgb(218, 219, 234);
  font-size: 16px;
  font-weight: 300;
  text-align: center;
  border-collapse: collapse;
  margin-top: 20px;
}

.level-table-header {
  background-color: rgb(17, 19, 36);
}

.level-table-col-title {
  font-size: 12px;
  text-transform: uppercase;
  font-weight: 700;
  color: rgb(93, 95, 114);
  padding-top: 24px;
  padding-bottom: 22px;
}

.level-table-row {
  background-color: rgb(14, 15, 30);
}

.level-table-row2 {
  position: relative;
  background-color: #131528;
}

.level-table-cell {
  position: relative;
  padding-top: 32px;
  padding-bottom: 32px;
  line-height: 1.2;
  white-space: nowrap;
}

.level-table-cell-sub {
  color: rgb(93, 95, 114);
  margin-top: 4px;
  font-size: 12px;
  text-transform: uppercase;
  line-height: 1;
  font-weight: 500;
}

.level-table-gold {
  color: rgb(112, 211, 152);
}

.avatar-uploader {
  > > > .el-upload {
    border: none !important;
  }
}

@media (min-width: 480px) {
}

@media (min-width: 768px) {
}

@media (min-width: 992px) {
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

  .account-items-header {
    flex-direction: column;
    justify-content: center;
    margin-bottom: 26px
  }

  .account-toggle {
    margin-bottom: 24px;
    max-width: 100%;
  }

  .account-items-buttons {
    flex-wrap: wrap;
    padding: 0 20px;
  }

  .account-items-btn {
    margin-bottom: 10px;
  }

  .account-content {
    overflow: hidden;
  }

  .account-tabs {
    overflow: auto;
    width: 100%;
  }
}

@media only screen and (max-width: 767px) {
  .live-item {
    flex-basis: 50%;
    max-width: 50%;
  }

  .account-inner {
    padding: 0;
  }

  .account-user {
    padding: 0 20px;
  }

  .account-edit-btn {
    width: 100px;
    font-size: 12px;

    svg {
      display: none;
    }
  }
}

@media only screen and (max-width: 479px) {
}
</style>
