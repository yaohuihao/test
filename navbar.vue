<template>
  <div>
    <div v-if="site.notice" style="padding: 10px;color: #a3a3a3;">公告: {{ site.notice }}</div>
        <!-- 手机端公告图片 -->
    <div class="navbar" v-if="mobile_menu_status">
    <div class="text-center">
      <el-carousel :interval="4000" arrow-direction="none">
      <el-carousel-item v-for="(item, index) in carouselItems" :key="index">
        <img :src="item.src" />
      </el-carousel-item>
    </el-carousel>
    </div>
  </div>
  <div v-else>
    <el-carousel :interval="4000" arrow-direction="none">
      <el-carousel-item v-for="(item, index) in carouselItems2" :key="index">
        <img :src="item.src" />
      </el-carousel-item>
    </el-carousel>
  </div>
    <header class="header">
      <div class="live">
        <div class="live__list-wrapper">
          <div class="live__buttons">
            <div class="live__btn-wrapper" @click="box_record_profit(true)">
              <button
                :class="'live__btn live__btn--top align-items-center ' + [this.profit_status == true ? 'live__btn--active' : '']"><span
                class="live__btn-text">Top</span>
                <div class="live__btn-icon">
                  <svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 16" width="18"
                       height="16">
                    <path
                      d="M9 .425a.563.563 0 0 0-.5353.3882L6.8204 5.875H1.4979a.562.562 0 0 0-.5347.3882.5649.5649 0 0 0 .2043.6299l4.3052 3.1281-1.6435 5.0611a.563.563 0 0 0 .5347.7368.5623.5623 0 0 0 .331-.1077L9 12.5833l4.3051 3.1281a.5653.5653 0 0 0 .3311.1077.563.563 0 0 0 .5347-.7368l-1.6443-5.0611 4.3059-3.1281a.5643.5643 0 0 0 .2043-.63.5623.5623 0 0 0-.5346-.3881h-5.3226L9.5355.8132A.563.563 0 0 0 9 .425Zm-1.4326.723c-2.301.4473-4.2283 1.9449-5.258 3.977h1.2964c.81-1.2893 2.065-2.2636 3.5515-2.7144l.4101-1.2627Zm2.8653 0 .4101 1.2626c1.4865.4508 2.7415 1.4251 3.5515 2.7144h1.2964c-1.0297-2.0321-2.957-3.5297-5.258-3.977Zm6.0534 6.9235-1.1294.8203c-.0798 1.293-.5466 2.48-1.2868 3.452l.4072 1.2531c1.2484-1.3391 2.023-3.1255 2.023-5.0969 0-.144-.005-.2867-.014-.4285ZM1.514 8.0723A6.7245 6.7245 0 0 0 1.5 8.5c0 1.9714.7746 3.7574 2.023 5.0969l.4065-1.2524c-.7399-.972-1.2063-2.1589-1.2861-3.452L1.514 8.0724Zm5.8938 6.5947-1.1228.8159C7.1276 15.8133 8.0416 16 9 16c.9586 0 1.8725-.1863 2.7151-.5171l-1.1228-.8159c-.5092.1331-1.0417.208-1.5922.208s-1.083-.0749-1.5923-.208Z"
                      fill="#5F616F"></path>
                  </svg>
                </div>
              </button>
            </div>
            <div class="live__btn-wrapper" @click="box_record_profit(false)">
              <button :class="'live__btn live__btn--all ' + [this.profit_status == false ? 'live__btn--active' : '']">
                <span class="live__btn-text">All</span>
                <div class="live__btn-icon">
                  <svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18"
                       height="19">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                          d="M9.5345 5.6644c0-1.8233 1.4781-3.3014 3.3014-3.3014 1.8233 0 3.3014 1.4781 3.3014 3.3014 0 1.8233-1.4781 3.3014-3.3014 3.3014-1.8233 0-3.3014-1.478-3.3014-3.3014ZM1.863 13.3357c0-1.8233 1.4781-3.3014 3.3014-3.3014 1.8233 0 3.3014 1.4781 3.3014 3.3014 0 1.8233-1.478 3.3014-3.3014 3.3014-1.8233 0-3.3014-1.4781-3.3014-3.3014Zm8.6715-3.3014c-.5523 0-1 .4477-1 1v3.6028c0 1.1045.8954 2 2 2h2.6028c1.1045 0 2-.8955 2-2v-3.6028c0-.5523-.4477-1-1-1h-4.6028ZM1.863 3.363c0-.5522.4478-1 1-1h4.6028c.5523 0 1 .4478 1 1v3.6028c0 1.1046-.8954 2-2 2H3.863c-1.1045 0-2-.8954-2-2V3.363Z"
                          fill="#5F616F"></path>
                  </svg>
                </div>
              </button>
            </div>
          </div>
          <div class="live__list">
            <template v-if="box_record_status">
              <li class="live__item drop-in" :style="'order: ' + item.order + ';'" v-for="(item, index) in box_record"  v-if="(profit_status && item.profit_status) || !profit_status">
                <router-link :to="'/profile/' + item.user.id"
                             :class="'live__link drop ' + color(item.ornaments.color, 3)">
                  <div class="live__front"><!---->
                    <template v-if="item.type == 2">
                      <div class="battle-icon"></div>
                    </template>
                    <template v-else-if="item.type == 3">
                      <div class="battle-icon"></div>
                    </template>
                    <div class="item__weapon-tag">{{ quality(item.ornaments.quality, 2) }}</div>
                    <div class="live__image-wrapper"><img
                      :src="img_url + item.ornaments.img"
                      width="110" height="82" alt=""
                      class="live__image"></div>
                    <div class="live__footer">
                      <div class="item__weapon-name live__subtitle text-left">{{ item.ornaments.name }}</div>
                      <div class="live__title" v-if="item.ornaments.appid == 730">{{
                          abrasion(item.ornaments.abrasion)
                        }}
                      </div>
                      <div class="live__title" v-else>{{ quality2(item.ornaments.quality2) }}</div>
                    </div>
                  </div>
                  <div class="live__back">
                    <div class="live__image-case-wrapper"><img
                      :src="img_url + item.box_img"
                      alt="" class="live__image-case"></div>
                    <div class="live__footer">
                      <div class="live__user-photo-wrapper"><img
                        :src="repair(item.user.portrait)"
                        alt="" class="live__user-photo"></div>
                      <div class="live__title">{{ item.user.name }}</div>
                    </div>
                  </div>
                </router-link>
              </li>
            </template>
            <template v-else>
              <li class="live__item" v-for="(item, index) in 20">
                <div :class="'live__link drop load ' + color(rand(1, 7), 3)">
                  <div class="loading-skeleton"></div>
                  <div class="loading-skeleton2"></div>
                </div>
              </li>
            </template>
          </div>
        </div>
      </div>
      <div>
        <div class="header__top-wrapper">
          <div class="header__top">
            <div class="container">
              <div class="header__top-inner">
                <div class="header__logo-wrapper">
                  <router-link to="/" class="header__logo-link router-link-active" title="Go to the main page">
                    <div class="header__logo-image"></div>
                  </router-link>
                </div>
                <div class="select-container type">
                  <el-select v-model="game_type" @change="game_type_change" placeholder="请选择"
                             popper-class="type-pop">
                    <el-option
                      label="CSGO"
                      :value="1">
                    </el-option>
                    <el-option
                      label="DOTA2"
                      :value="2">
                    </el-option>
                  </el-select>
                </div>
                <div class="header__online-counter">
                  <svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"
                       class="header__online-icon">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                          d="m6.952 25.6684-.0002.0003.0107.0091c.2542.2169.5834.3312.9202.3216.3367-.0095.6587-.1421.8994-.3733.2411-.2314.3826-.5444.3928-.8755.0101-.3311-.112-.6518-.3388-.8968l.0003-.0003-.01-.0097C6.7964 21.8942 5.658 19.2525 5.658 16.5c0-2.7524 1.1384-5.3942 3.1682-7.3438l.0003.0003.0095-.0103c.2268-.245.349-.5657.3388-.8968-.0102-.331-.1517-.644-.3927-.8755-.2408-.2312-.5628-.3638-.8995-.3734-.3368-.0095-.666.1048-.9202.3216l-.0002-.0003-.0102.0098C4.4226 9.7624 3 13.06 3 16.5s1.4226 6.7376 3.952 9.1684Zm3.8684-3.4457-.0004.0005.0141.0116c.2552.2103.582.3191.915.3069.3331-.0122.6507-.1446.8889-.3733.2384-.229.3797-.5377.3927-.8651.013-.3274-.1034-.6459-.3234-.8921l.0005-.0005-.0131-.0126c-1.077-1.0342-1.6802-2.4348-1.6802-3.8932 0-1.4555.6009-2.8536 1.674-3.8873a1.2936 1.2936 0 0 0 .3268-.4111 1.254 1.254 0 0 0 .1333-.5142 1.2508 1.2508 0 0 0-.0922-.5228 1.2862 1.2862 0 0 0-.3006-.4412 1.3373 1.3373 0 0 0-.4556-.2864 1.3727 1.3727 0 0 0-.5341-.0869 1.3707 1.3707 0 0 0-.526.1258 1.3288 1.3288 0 0 0-.4259.3124c-1.5727 1.5148-2.4578 3.5685-2.4578 5.7117 0 2.1461.8874 4.2024 2.464 5.7178Zm1.9816-10.123a1.0574 1.0574 0 0 1-.2729.3404c-1.1226 1.0782-1.7533 2.5403-1.7533 4.0648 0 1.5246.6307 2.9867 1.7533 4.0648.1796.201.2736.4596.2631.7242-.0105.2646-.1247.5156-.3197.7029-.1951.1872-.4565.2968-.7321.3069H11.74c.2756-.0101.537-.1197.732-.3069.1951-.1873.3092-.4383.3198-.7029.0105-.2646-.0835-.5232-.2631-.7242-1.1226-1.0781-1.7533-2.5402-1.7533-4.0648 0-1.5245.6307-2.9866 1.7533-4.0647a1.0572 1.0572 0 0 0 .2728-.3405 1.0123 1.0123 0 0 0 .0334-.8403 1.0462 1.0462 0 0 0-.245-.3594 1.098 1.098 0 0 0-.3744-.2353 1.1346 1.1346 0 0 0 .0005 0c.1403.0528.2677.1329.3743.2352.1066.1024.19.2247.245.3595a1.0123 1.0123 0 0 1-.0333.8403Zm-3.8657-3.843c-.0082-.2675-.1226-.522-.3197-.7112-.197-.1892-.462-.299-.7407-.3069a1.1283 1.1283 0 0 0-.0004 0c.2786.0079.5436.1177.7407.307.1971.1892.3115.4436.3197.711.0082.2676-.0904.528-.2756.728C6.585 10.9782 5.419 13.6814 5.419 16.5s1.1659 5.5219 3.2413 7.5154c.1852.2.2838.4604.2756.7279-.0082.2676-.1226.522-.3197.7112-.197.1892-.462.299-.7407.3069h.0004c.2787-.0079.5436-.1177.7407-.3069.1971-.1893.3115-.4437.3197-.7112.0082-.2675-.0904-.5279-.2755-.7279-2.0755-1.9935-3.2414-4.6968-3.2414-7.5154s1.1659-5.5219 3.2414-7.5154c.1851-.2.2837-.4604.2755-.728Zm17.1012 17.4211.0002.0004.0102-.0098C28.5774 23.2376 30 19.9401 30 16.5s-1.4226-6.7376-3.9521-9.1684l.0003-.0003-.0107-.0091c-.2542-.217-.5834-.3312-.9202-.3216-.3367.0095-.6587.1421-.8995.3733-.241.2314-.3825.5444-.3927.8755-.0101.331.112.6518.3388.8968l-.0003.0003.0101.0097c2.0298 1.9496 3.1682 4.5913 3.1682 7.3438 0 2.7525-1.1384 5.3942-3.1682 7.3438l-.0003-.0003-.0095.0103c-.2268.245-.3489.5657-.3388.8968.0102.3311.1517.6441.3927.8755.2408.2312.5628.3638.8995.3733.3368.0096.666-.1047.9202-.3216Zm-3.8198-3.443.0005.0006.0132-.0127c1.5766-1.5154 2.464-3.5718 2.464-5.7178 0-2.1432-.8851-4.1969-2.4578-5.7117a1.3288 1.3288 0 0 0-.4259-.3124 1.3707 1.3707 0 0 0-.526-.1258 1.3727 1.3727 0 0 0-.5341.0869 1.3373 1.3373 0 0 0-.4556.2864 1.2862 1.2862 0 0 0-.3006.4412 1.2508 1.2508 0 0 0-.0922.5228c.0071.1787.0526.3538.1333.5142.0792.1575.1906.2973.3268.4111 1.0731 1.0337 1.674 2.4318 1.674 3.8873 0 1.4584-.6032 2.859-1.6802 3.8932l-.0005-.0005-.0121.0136c-.22.2462-.3364.5647-.3234.8921.013.3274.1543.6361.3927.8651.2382.2287.5558.3611.8889.3733.333.0122.6598-.0966.915-.3069Zm-.5066-11.538c.1358.0629.2565.1521.3546.2619 1.531 1.4715 2.391 3.4663 2.391 5.5462s-.86 4.0747-2.391 5.5463a1.1207 1.1207 0 0 1-.6868.253 1.1212 1.1212 0 0 0 .6864-.253c1.531-1.4715 2.391-3.4664 2.391-5.5463 0-2.0798-.86-4.0747-2.391-5.5462a1.0897 1.0897 0 0 0-.3546-.2619 1.1293 1.1293 0 0 0 .0004 0Zm3.5672-3.452c.223.024.4344.1136.604.2584 2.4838 2.3869 3.879 5.6228 3.879 8.9968 0 3.374-1.3952 6.6099-3.879 8.9968-.1946.1661-.444.2596-.703.2648a1.1186 1.1186 0 0 0 .7026-.2647c2.4838-2.3869 3.879-5.6229 3.879-8.9969 0-3.374-1.3952-6.61-3.879-8.9968a1.1148 1.1148 0 0 0-.6036-.2585ZM19.1522 16.5c0-1.4615-1.1875-2.6462-2.6524-2.6462a3.4004 3.4004 0 0 0 .0005 0c1.4648 0 2.6523 1.1847 2.6523 2.6462 0 1.4213-1.1231 2.5809-2.5322 2.6436 1.4089-.0629 2.5318-1.2224 2.5318-2.6436Zm.2391 0c0-1.593-1.2944-2.8844-2.891-2.8844-1.5967 0-2.8911 1.2914-2.8911 2.8844 0 1.593 1.2944 2.8844 2.8911 2.8844 1.5966 0 2.891-1.2914 2.891-2.8844Z"
                          fill="url(#a)"></path>
                    <defs>
                      <radialGradient id="a" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse"
                                      gradientTransform="matrix(13.5 0 0 9.5 16.5 16.5)">
                        <stop stop-color="#43B570"></stop>
                        <stop offset="1" stop-color="#43B570" stop-opacity="0.29"></stop>
                      </radialGradient>
                    </defs>
                  </svg>
                  <div class="header__online">
                    <div class="header__online-cnt">
                      <div class="css-12ju129-SkeletonTheme">215</div>
                    </div>
                    <div class="header__online-label">在线</div>
                  </div>
                </div>
                <ul class="header__top-menu">
                  <li class="header__top-menu-item">
                    <router-link class="header__top-menu-link header__top-menu-link--faq" to="/support">
                      FAQ
                      <svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                           width="20" height="20" class="header__top-menu-icon">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M.5881 8.507v.3065c0 3.2423 2.4543 5.9246 5.647 6.3707V16.32c0 1.6207 1.9351 2.5061 3.2096 1.4685l3.1193-2.5393c3.7869-.0285 6.8477-3.0361 6.8477-6.7422 0-3.7237-3.0902-6.7424-6.902-6.7424H7.4901C3.6782 1.7646.588 4.7833.588 8.507Zm11.0012 5.142-3.3496 2.7269c-.0485.0395-.1221.0058-.1221-.0559v-1.99a.9015.9015 0 0 0-.0285-.2252c-.1028-.3989-.4725-.6942-.9127-.6942-2.599 0-4.706-2.0582-4.706-4.597V8.507c0-2.7081 2.2474-4.9035 5.0197-4.9035h5.0196c2.7723 0 5.0196 2.1954 5.0196 4.9035 0 2.7082-2.2473 4.9036-5.0196 4.9036h-.2462a1.069 1.069 0 0 0-.6742.2384ZM5.7646 7.2548c-.7797 0-1.4118.6146-1.4118 1.3726s.6321 1.3725 1.4118 1.3725 1.4118-.6145 1.4118-1.3725-.6321-1.3726-1.4118-1.3726Zm2.8235 1.3726c0-.758.6321-1.3726 1.4118-1.3726s1.4118.6146 1.4118 1.3726-.6321 1.3725-1.4118 1.3725-1.4118-.6145-1.4118-1.3725Zm5.6471-1.3726c-.7797 0-1.4118.6146-1.4118 1.3726s.6321 1.3725 1.4118 1.3725 1.4118-.6145 1.4118-1.3725-.6321-1.3726-1.4118-1.3726Z"
                              fill="#CECFD6"></path>
                      </svg>
                    </router-link>
                  </li>
                  <li class="header__top-menu-item">
                    <router-link class="header__top-menu-link header__top-menu-link--faq" to="/partner">
                      合作伙伴
                      <svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                           width="20" height="20" class="header__top-menu-icon">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M10 10c-1.4912 0-2.7 1.2592-2.7 2.8125v3.75c0 .5178-.403.9375-.9.9375s-.9-.4197-.9-.9375v-3.75l.0001-.0332a3.0533 3.0533 0 0 1-.7439.0191.8662.8662 0 0 1-.1562.0141h-.3857c-.7811 0-1.4143.6596-1.4143 1.4732v1.3393c0 .5178-.403.9375-.9.9375s-.9-.4197-.9-.9375v-1.3393c0-1.1916.5976-2.2378 1.4976-2.8312-.376-.5404-.5976-1.2049-.5976-1.9232C1.9 7.719 3.3103 6.25 5.05 6.25a3.034 3.034 0 0 1 1.3631.3223A3.957 3.957 0 0 1 6.4 6.25C6.4 4.179 8.0118 2.5 10 2.5c1.9882 0 3.6 1.679 3.6 3.75 0 .1086-.0044.216-.0131.3223A3.034 3.034 0 0 1 14.95 6.25c1.7397 0 3.15 1.469 3.15 3.2813 0 .7183-.2216 1.3828-.5976 1.9232.9.5934 1.4976 1.6396 1.4976 2.8312v1.3393c0 .5178-.4029.9375-.9.9375-.4971 0-.9-.4197-.9-.9375v-1.3393c0-.8136-.6332-1.4732-1.4143-1.4732H15.4a.867.867 0 0 1-.1563-.0141 3.0677 3.0677 0 0 1-.7438-.0191l.0001.0332v3.75c0 .5178-.4029.9375-.9.9375-.4971 0-.9-.4197-.9-.9375v-3.75C12.7 11.2592 11.4912 10 10 10Zm0-1.875c-.9941 0-1.8-.8395-1.8-1.875 0-1.0355.8059-1.875 1.8-1.875.9941 0 1.8.8395 1.8 1.875 0 1.0355-.8059 1.875-1.8 1.875Zm-4.95 0c-.7456 0-1.35.6296-1.35 1.4063 0 .7766.6044 1.4062 1.35 1.4062s1.35-.6296 1.35-1.4063c0-.7766-.6044-1.4062-1.35-1.4062Zm9.9 0c-.7456 0-1.35.6296-1.35 1.4063 0 .7766.6044 1.4062 1.35 1.4062s1.35-.6296 1.35-1.4063c0-.7766-.6044-1.4062-1.35-1.4062Z"
                              fill="#CECFD6"></path>
                      </svg>
                    </router-link>
                  </li>
                  <li class="header__top-menu-item" v-if="site.qq_group">
                    <a class="header__top-menu-link header__top-menu-link--faq" :href="site.qq_group" target="_blank">
                      加入Q群
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                           viewBox="0 0 512 512" class="header__top-menu-icon">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M510.228,166.833L412.915,42.98c-1.573-2-3.978-3.169-6.522-3.169H105.606c-2.545,0-4.95,1.169-6.521,3.169L1.772,166.833    c-2.435,3.1-2.352,7.485,0.198,10.49l247.706,291.94c1.576,1.858,3.888,2.927,6.324,2.927c2.436,0,4.748-1.07,6.324-2.927    l247.706-291.94C512.58,174.318,512.663,169.932,510.228,166.833z M394.573,56.398l-35.771,98.898l-84.771-98.898H394.573z     M344.127,163.664H167.872l88.127-102.815L344.127,163.664z M237.968,56.398l-84.77,98.898l-35.773-98.898H237.968z     M102.891,64.984l35.694,98.68H25.357L102.891,64.984z M26.207,180.251h118.377l87.96,243.183L26.207,180.251z M255.999,439.514    l-93.776-259.263h187.551L255.999,439.514z M279.455,423.433l87.959-243.182h118.377L279.455,423.433z M373.413,163.664    l35.693-98.681l77.535,98.681H373.413z"
                              fill="#CECFD6"></path>
                      </svg>
                    </a>
                  </li>
                  <li class="header__top-menu-item header__top-menu-item1" v-if="site.qq_group" >                
                     <router-link style="font-size:11px;padding-right: 0px;padding: 11px 8px 8px 8px;" class="header-account__link header__top-menu-link header__top-menu-link--faq" to="/balance_info">
                        CDK补贴计划
                     </router-link>              
                  </li>
                </ul>
                <div class="auth">
                  <div v-if="user.id" class="mr-lg-2 pt-2 pb-2 d-flex justify-content-center">
                    <div class="header-account">
                      <div class="header-account__refill-btn-1">
                        <router-link class="header-account__link" to="/balance_info" style="font-size:11px;">
                             CDK补贴计划
                        </router-link>
                      </div>
                      <div class="header-account__refill-wrapper" @click="recharge">
                        <button class="header-account__refill-btn"><!---->
                          <div class="header-account__balance2">
                            $ {{ user.bean }}
                          </div>
                          <svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22 22" width="22" height="22"
                               class="header-account__refill-icon">
                            <path
                              d="M13.6125 3.6208v3.4833h-1.375V3.6208c0-.2475-.22-.3666-.3666-.3666a.3675.3675 0 0 0-.1375.0274L4.4642 6.0225c-.4859.1833-.7975.6417-.7975 1.1642v.6141a3.4243 3.4243 0 0 0-1.375 2.75V7.1866c0-1.0908.6692-2.0625 1.6866-2.4474l7.2784-2.75a1.811 1.811 0 0 1 .6142-.11c.9166 0 1.7416.7424 1.7416 1.7416ZM19.7083 13.2917v.9167c0 .2475-.1925.4491-.4492.4583h-1.3383c-.4858 0-.9258-.3575-.9625-.8342-.0275-.2841.0825-.55.2658-.7333.165-.1742.3942-.2658.6417-.2658H19.25c.2658.0091.4583.2108.4583.4583Z"
                              fill="#4F3D2E"></path>
                            <path
                              d="M17.8567 11.8708h.935c.5042 0 .9167-.4125.9167-.9167v-.4033c0-1.8975-1.5492-3.4467-3.4467-3.4467H5.7383c-.7791 0-1.4941.2567-2.0716.6967a3.4244 3.4244 0 0 0-1.375 2.75V16.72c0 1.8975 1.5492 3.4466 3.4466 3.4466h10.5234c1.8975 0 3.4467-1.5491 3.4467-3.4466v-.1742c0-.5042-.4125-.9167-.9167-.9167h-.7975c-.88 0-1.7233-.5408-1.9525-1.3933-.1925-.6967.0367-1.3658.495-1.815.3392-.3483.8067-.55 1.32-.55Zm-5.0233-.1833H6.4167c-.3758 0-.6875-.3117-.6875-.6875 0-.3759.3117-.6875.6875-.6875h6.4167c.3758 0 .6875.3116.6875.6875 0 .3758-.3117.6875-.6875.6875Z"
                              fill="#4F3D2E"></path>
                          </svg>
                        </button>
                      </div>
                      <router-link class="header-account__link" to="/user_info">
                        <div class="header-account__balance">
                          <div class="header-account__username">{{ user.name }}</div>
                          <div>
                            $ {{ user.bean }}
                          </div>
                        </div>
                        <span class="header-account__photo-wrapper"><img
                          :src="repair(user.portrait)" width="50" height="50"
                          alt="" class="header-account__photo"></span>
                      </router-link>
                      <div class="header-account__menu">
                        <div class="account-menu">
                          <button class="account-menu__btn" @click="menu">
                            <svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28" width="28"
                                 height="28" class="account-menu__icon">
                              <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M4.6667 2.3334c-1.2887 0-2.3333 1.0446-2.3333 2.3333V10.5c0 1.2887 1.0446 2.3334 2.3333 2.3334H10.5c1.2887 0 2.3334-1.0447 2.3334-2.3334V4.6667c0-1.2887-1.0447-2.3333-2.3334-2.3333H4.6667Zm0 2.3333H10.5V10.5H4.6667V4.6667ZM17.5 2.3334c-1.2886 0-2.3333 1.0446-2.3333 2.3333V10.5c0 1.2887 1.0447 2.3334 2.3333 2.3334h5.8334c1.2886 0 2.3333-1.0447 2.3333-2.3334V4.6667c0-1.2887-1.0447-2.3333-2.3333-2.3333H17.5Zm0 2.3333h5.8334V10.5H17.5V4.6667ZM17.5 15.1667c-1.2886 0-2.3333 1.0447-2.3333 2.3333v5.8334c0 1.2886 1.0447 2.3333 2.3333 2.3333h5.8334c1.2886 0 2.3333-1.0447 2.3333-2.3333V17.5c0-1.2886-1.0447-2.3333-2.3333-2.3333H17.5Zm0 2.3333h5.8334v5.8334H17.5V17.5ZM4.6667 15.1667c-1.2887 0-2.3333 1.0447-2.3333 2.3333v5.8334c0 1.2886 1.0446 2.3333 2.3333 2.3333H10.5c1.2887 0 2.3334-1.0447 2.3334-2.3333V17.5c0-1.2886-1.0447-2.3333-2.3334-2.3333H4.6667Zm0 2.3333H10.5v5.8334H4.6667V17.5Z"
                                    fill="#686C7C"></path>
                            </svg>
                          </button>
                          <div v-if="menu_status">
                            <div class="window-overlay" @click.stop="page_close"></div>
                            <div class="account-menu__content" style="z-index: 1050;">
                              <ul class="account-menu__content-menu">
                                <li class="account-menu__content-item">
                                  <router-link class="account-menu__content-link account-menu__content-link--profile"
                                               to="/user_info" @click.native.stop="page_close">
                                    <div class="account-menu__link-label">账户明细</div>
                                  </router-link>
                                </li>
                                <li class="account-menu__content-item">
                                  <router-link class="account-menu__content-link account-menu__content-link--refill"
                                               to="/user_info/recharge_record" @click.native.stop="page_close">
                                    <div class="account-menu__link-label">充值记录</div>
                                  </router-link>
                                </li>
                                <li class="account-menu__content-item">
                                  <router-link class="account-menu__content-link account-menu__content-link--refer"
                                               to="/partner" @click.native.stop="page_close">
                                    <div class="account-menu__link-label">推广记录</div>
                                  </router-link>
                                </li>
                                <li class="account-menu__content-item">
                                  <router-link
                                    class="account-menu__content-link account-menu__content-link--partner-history"
                                    to="/user_info/partner_history" @click.native.stop="page_close">
                                    <div class="account-menu__link-label">合作伙伴历史</div>
                                  </router-link>
                                </li>
                              </ul>
                              <div class="account-menu__content-additional">
                                <div class="account-menu__agreement-wrapper"></div>
                                <div class="account-menu__logout-wrapper">
                                  <button class="account-menu__logout-btn" @click.stop="log_out">
                                    退出登录
                                    <svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 17" width="16"
                                         height="17" class="account-menu__logout-icon">
                                      <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M7.532 1.9226c.8716-.339 1.8014.3294 1.8014 1.2952V13.7828c0 .8998-.8152 1.5601-1.6567 1.3419l-4-1.0374c-.5936-.154-1.01-.7072-1.01-1.3419V4.7739c0-.5766.3448-1.0927.8652-1.2952l4-1.556ZM8 3.2178l-4 1.556v7.9716l4 1.0374V3.2178ZM6 8.4442c0-.382.2985-.6916.6667-.6916.3682 0 .6667.3096.6667.6916s-.2985.6916-.6667.6916c-.3681 0-.6666-.3097-.6666-.6916Zm4.6667-5.5328c-.3681 0-.6666.3096-.6666.6916s.2985.6916.6666.6916h1.3334V12.5938h-1.3334c-.3681 0-.6666.3096-.6666.6916 0 .3819.2985.6916.6666.6916h1.3334c.7364 0 1.3333-.6193 1.3333-1.3832V4.2946c0-.764-.5969-1.3832-1.3333-1.3832h-1.3334Z"
                                            fill="#474752"></path>
                                    </svg>
                                  </button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div v-else class="mr-lg-2 pt-2 pb-2 d-flex justify-content-center"><!---->
                    <button class="i-btn primary auth__btn i-btn--sm" @click="steam_login"><!----><span
                      class="auth__text mr-2">使用Steam登陆</span>
                      <div class="d-flex" style="font-size: 24px;">
                        <svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="steam"
                             role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"
                             class="svg-inline--fa fa-steam">
                          <path fill="currentColor"
                                d="M496 256c0 137-111.2 248-248.4 248-113.8 0-209.6-76.3-239-180.4l95.2 39.3c6.4 32.1 34.9 56.4 68.9 56.4 39.2 0 71.9-32.4 70.2-73.5l84.5-60.2c52.1 1.3 95.8-40.9 95.8-93.5 0-51.6-42-93.5-93.7-93.5s-93.7 42-93.7 93.5v1.2L176.6 279c-15.5-.9-30.7 3.4-43.5 12.1L0 236.1C10.2 108.4 117.1 8 247.6 8 384.8 8 496 119 496 256zM155.7 384.3l-30.5-12.6a52.79 52.79 0 0 0 27.2 25.8c26.9 11.2 57.8-1.6 69-28.4 5.4-13 5.5-27.3.1-40.3-5.4-13-15.5-23.2-28.5-28.6-12.9-5.4-26.7-5.2-38.9-.6l31.5 13c19.8 8.2 29.2 30.9 20.9 50.7-8.3 19.9-31 29.2-50.8 21zm173.8-129.9c-34.4 0-62.4-28-62.4-62.3s28-62.3 62.4-62.3 62.4 28 62.4 62.3-27.9 62.3-62.4 62.3zm.1-15.6c25.9 0 46.9-21 46.9-46.8 0-25.9-21-46.8-46.9-46.8s-46.9 21-46.9 46.8c.1 25.8 21.1 46.8 46.9 46.8z"
                                class=""></path>
                        </svg>
                      </div>
                    </button>
                    <button class="i-btn primary ml-2 auth__btn i-btn--sm" @click="user_login"><!----><span
                      class="auth__text mr-2">手机号登录</span><span class="d-flex"
                                                                     style="font-size: 24px;"><svg
                      aria-hidden="true" focusable="false" data-prefix="fas" data-icon="envelope"
                      role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                      class="svg-inline--fa fa-envelope"><path fill="currentColor"
                                                               d="M502.3 190.8c3.9-3.1 9.7-.2 9.7 4.7V400c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V195.6c0-5 5.7-7.8 9.7-4.7 22.4 17.4 52.1 39.5 154.1 113.6 21.1 15.4 56.7 47.8 92.2 47.6 35.7.3 72-32.8 92.3-47.6 102-74.1 131.6-96.3 154-113.7zM256 320c23.2.4 56.6-29.2 73.4-41.4 132.7-96.3 142.8-104.7 173.4-128.7 5.8-4.5 9.2-11.5 9.2-18.9v-19c0-26.5-21.5-48-48-48H48C21.5 64 0 85.5 0 112v19c0 7.4 3.4 14.3 9.2 18.9 30.6 23.9 40.7 32.4 173.4 128.7 16.8 12.2 50.2 41.8 73.4 41.4z"
                                                               class=""></path></svg></span></button><!----></div>
                </div>
                <div class="mobile-menu">
                  <button class="mobile-menu__btn" @click="mobile_menu">
                    <svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28"
                         width="28" height="28" class="mobile-menu__icon">
                      <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M4.6667 2.3334c-1.2887 0-2.3333 1.0446-2.3333 2.3333V10.5c0 1.2887 1.0446 2.3334 2.3333 2.3334H10.5c1.2887 0 2.3334-1.0447 2.3334-2.3334V4.6667c0-1.2887-1.0447-2.3333-2.3334-2.3333H4.6667Zm0 2.3333H10.5V10.5H4.6667V4.6667ZM17.5 2.3334c-1.2886 0-2.3333 1.0446-2.3333 2.3333V10.5c0 1.2887 1.0447 2.3334 2.3333 2.3334h5.8334c1.2886 0 2.3333-1.0447 2.3333-2.3334V4.6667c0-1.2887-1.0447-2.3333-2.3333-2.3333H17.5Zm0 2.3333h5.8334V10.5H17.5V4.6667ZM17.5 15.1667c-1.2886 0-2.3333 1.0447-2.3333 2.3333v5.8334c0 1.2886 1.0447 2.3333 2.3333 2.3333h5.8334c1.2886 0 2.3333-1.0447 2.3333-2.3333V17.5c0-1.2886-1.0447-2.3333-2.3333-2.3333H17.5Zm0 2.3333h5.8334v5.8334H17.5V17.5ZM4.6667 15.1667c-1.2887 0-2.3333 1.0447-2.3333 2.3333v5.8334c0 1.2886 1.0446 2.3333 2.3333 2.3333H10.5c1.2887 0 2.3334-1.0447 2.3334-2.3333V17.5c0-1.2886-1.0447-2.3333-2.3334-2.3333H4.6667Zm0 2.3333H10.5v5.8334H4.6667V17.5Z"
                            fill="#686C7C"></path>
                    </svg>
                  </button>
                  <div v-if="mobile_menu_status">
                    <div class="mobile-menu__content mobile-menu__animation-enter-done">
                      <ul class="mobile-menu__list text-left">
                        <li class="mobile-menu__item">
                          <router-link class="mobile-menu__item-link mobile-menu__item-link--bonuses" to="/roll"
                                       @click.native.stop="page_close">
                            福利roll房
                          </router-link>
                        </li>
                        <li class="mobile-menu__item">
                          <router-link class="mobile-menu__item-link mobile-menu__item-link--bonuses" to="/lucky"
                                       @click.native.stop="page_close">
                            幸运开箱
                          </router-link>
                        </li>
                        <li class="mobile-menu__item">
                          <router-link class="mobile-menu__item-link mobile-menu__item-link--bonuses" to="/"
                                       @click.native.stop="page_close">
                            盲盒开箱
                          </router-link>
                        </li>
                        <li class="mobile-menu__item">
                          <router-link class="mobile-menu__item-link mobile-menu__item-link--bonuses" to="/battle"
                                       @click.native.stop="page_close">
                            箱子对战
                          </router-link>
                        </li>
                        <li class="mobile-menu__item">
                          <router-link class="mobile-menu__item-link mobile-menu__item-link--bonuses" to="/store"
                                       @click.native.stop="page_close">
                            饰品商城
                          </router-link>
                        </li>
                        <li class="mobile-menu__item">
                          <router-link class="mobile-menu__item-link mobile-menu__item-link--bonuses" to="/testApi"
                                       @click.native.stop="page_close">
                            测试API
                          </router-link>
                        </li>
                        <li class="mobile-menu__item">
                          <router-link class="mobile-menu__item-link mobile-menu__item-link--bonuses" to="/support"
                                       @click.native.stop="page_close">
                            FAQ
                          </router-link>
                        </li>
                        <li class="mobile-menu__item">
                          <router-link class="mobile-menu__item-link mobile-menu__item-link--bonuses" to="/partner"
                                       @click.native.stop="page_close">
                            合作伙伴
                          </router-link>
                        </li>
                        <li class="mobile-menu__item">
                          <button class="mobile-menu__item-link mobile-menu__item-link--logout" @click.stop="log_out">
                            退出登录
                          </button>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="header__bottom">
          <div class="container">
            <div class="header__bottom-inner">
              <ul class="header__bonus-menu">
                <li class="header__bonus-menu-item">
                  <router-link class="header__bonus-menu-link header__bonus-menu-link--bonuses" to="/roll">
                    <span class="header__bonus-menu-text">福利roll房</span>
                  </router-link>
                </li>
                <li class="header__bonus-menu-item">
                  <router-link class="header__bonus-menu-link header__bonus-menu-link--wheel" to="/lucky">
                    <span class="header__bonus-menu-text">幸运开箱</span>
                  </router-link>
                </li>
                <li class="header__bonus-menu-item">
                  <router-link class="header__bonus-menu-link header__bonus-menu-link--wheel" to="/">
                    <span class="header__bonus-menu-text">盲盒开箱</span>
                  </router-link>
                </li>
                <li class="header__bonus-menu-item">
                  <router-link class="header__bonus-menu-link header__bonus-menu-link--giveaway" to="/battle">
                    <span class="header__bonus-menu-text">箱子对战</span>
                  </router-link>
                </li>
                <li class="header__bonus-menu-item">
                  <router-link class="header__bonus-menu-link header__bonus-menu-link--bonuses" to="/store">
                    <span class="header__bonus-menu-text">饰品商城</span>
                  </router-link>
                </li>
                <li class="header__bonus-menu-item" v-if="api_token != '' && api_token != null">
                  <router-link class="header__bonus-menu-link header__bonus-menu-link--bonuses" to="/testApi">
                    <span class="header__bonus-menu-text">测试API</span>
                  </router-link>
                </li>
              </ul>
              <div class="header__cases-filter"></div>
            </div>
          </div>
        </div>
      </div>
    </header>
    <el-dialog
      class="recharge-dialog"
      append-to-body
      title="余额充值"
      :visible.sync="recharge_page"
      :close-on-click-modal="false"
      :lock-scroll="false">
      <div class="recharge-info">
        <el-radio-group v-model="recharge_type" style="margin-bottom: 20px;">
          <el-radio-button label="1">支付宝充值1</el-radio-button>
          <el-radio-button label="2">支付宝充值2</el-radio-button>
          <el-radio-button label="3">CDK</el-radio-button>
        </el-radio-group>
        <template v-if="recharge_type == '1'">
          <input class="recharge-input" type="number" v-model="recharge_num" placeholder="请输入充值金额">
          <button class="recharge-btn" @click="to_recharge">充值</button>
        </template>
        <template v-if="recharge_type == '2'">
          <input class="recharge-input" type="number" v-model="recharge_num" placeholder="请输入充值金额">
          <button class="recharge-btn" @click="to_recharge">充值</button>
        </template>
        <template v-if="recharge_type == '3'">
          <input class="recharge-input" type="text" v-model="cdk" placeholder="请输入CDK">
          <button class="recharge-btn" @click="to_cdk">充值</button>
        </template>
      </div>
    </el-dialog>
    <el-dialog
      class="recharge-dialog pay-img"
      append-to-body
      title="支付宝付款"
      :visible.sync="recharge_page2"
      :close-on-click-modal="false"
      :lock-scroll="false">
      <div class="recharge-info">
        <img :src="pay_img" alt="">
      </div>
    </el-dialog>
    <el-dialog
      align="left"
      class="recharge-dialog"
      title="手机登录"
      append-to-body
      :visible.sync="login_page"
      :close-on-click-modal="false"
      :lock-scroll="false"
      width="500px">
      <div class="recharge-info">
        <el-form
          ref="login_form"
          :rules="login_rule"
          :model="login_form">
          <el-row>
            <el-col :span="24">
              <el-form-item prop="phone">
                <el-input v-model="login_form.phone" placeholder="手机号码"></el-input>
              </el-form-item>
            </el-col>
            <el-col :span="24">
              <el-form-item prop="password">
                <el-input type="password" v-model="login_form.password" placeholder="登录密码"></el-input>
              </el-form-item>
            </el-col>
          </el-row>
        </el-form>
        <button class="recharge-btn recharge-btn3" @click="login">登录</button>
        <div class="login-option">
          <div @click="user_forgot">忘记密码?</div>
          <div @click="user_register">注册账号>></div>
        </div>
      </div>
    </el-dialog>
    <el-dialog
      align="left"
      class="recharge-dialog"
      title="忘记密码"
      append-to-body
      :visible.sync="forgot_page"
      :close-on-click-modal="false"
      :lock-scroll="false"
      width="500px">
      <div class="recharge-info">
        <el-form
          ref="forgot_form"
          :rules="forgot_rule"
          :model="forgot_form">
          <el-row>
            <el-col :span="24">
              <el-form-item prop="phone">
                <el-input v-model="forgot_form.phone" placeholder="手机号码"></el-input>
              </el-form-item>
            </el-col>
            <el-col :span="24">
              <el-form-item prop="password">
                <el-input type="password" v-model="forgot_form.password" placeholder="新密码"></el-input>
              </el-form-item>
            </el-col>
            <el-col :span="24">
              <el-form-item prop="vif">
                <el-input v-model="forgot_form.vif" placeholder="验证码">
                  <el-button slot="append" icon="el-icon-s-promotion" @click="get_vif2"></el-button>
                </el-input>
              </el-form-item>
            </el-col>
          </el-row>
        </el-form>
        <button class="recharge-btn recharge-btn3" @click="forgot">重置密码</button>
        <div class="login-option">
          <div @click="user_login">又想起来了>></div>
        </div>
      </div>
    </el-dialog>
    <el-dialog
      align="left"
      class="recharge-dialog"
      title="手机注册"
      append-to-body
      :visible.sync="register_page"
      :close-on-click-modal="false"
      :lock-scroll="false"
      width="500px">
      <div class="recharge-info">
        <el-form
          ref="register_form"
          :rules="register_rule"
          :model="register_form">
          <el-row>
            <el-col :span="24">
              <el-form-item prop="phone">
                <el-input v-model="register_form.phone" placeholder="手机号码"></el-input>
              </el-form-item>
            </el-col>
            <el-col :span="24">
              <el-form-item prop="password">
                <el-input type="password" v-model="register_form.password" placeholder="登录密码"></el-input>
              </el-form-item>
            </el-col>
            <el-col :span="24">
              <el-form-item prop="vif">
                <el-input v-model="register_form.vif" placeholder="验证码">
                  <el-button slot="append" icon="el-icon-s-promotion" @click="get_vif"></el-button>
                </el-input>
              </el-form-item>
            </el-col>
            <el-col :span="24">
              <el-form-item>
                <el-input type="text" v-model="register_form.invitation_code" placeholder="邀请码 可不填"></el-input>
              </el-form-item>
            </el-col>
          </el-row>
        </el-form>
        <button class="recharge-btn recharge-btn3" @click="register">注册</button>
        <div class="login-option">
          <div @click="user_login">已有账号?点击登录>></div>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import {ttGet, ttPost, ttWebSocket, abrasion, quality, quality2, color, repair, rand} from "@/utils/util.js";
import {getStore, removeStore, setStore} from "@/utils/store.js";
import Vue from 'vue';

export default {
  name: "Navbar",
  data() {
    return {
            // 图片数据
      carouselItems: [
        { src: require("@/assets/img/hd2-1.jpg") },
        { src: require("@/assets/img/hd2-2.jpg") },
      ],
      carouselItems2: [
        { src: require("@/assets/img/hd1.jpg") },
        { src: require("@/assets/img/hd1.jpg") },
      ],
      server_url: this.$server_url,
      img_url: this.$img_url,
      site: {},
      active: '/',
      game_type: 1,
      box_record_status: false,
      box_record: [],
      profit_status: false,
      new_max: {},
      user: {},
      page: false,
      recharge_page: false,
      recharge_page2: false,
      recharge_type: '1',
      pay_img: '',
      recharge_num: '',
      cdk: '',
      login_page: false,
      login_form: {
        phone: '',
        password: '',
      },
      login_rule: {
        phone: [
          {required: true, message: "请输入手机号码", trigger: "blur"}
        ],
        password: [
          {required: true, message: "请输入密码", trigger: "blur"}
        ]
      },
      forgot_page: false,
      forgot_form: {
        phone: '',
        password: '',
        vif: '',
      },
      forgot_rule: {
        phone: [
          {required: true, message: "请输入手机号码", trigger: "blur"}
        ],
        password: [
          {required: true, message: "请输入密码", trigger: "blur"}
        ],
        vif: [
          {required: true, message: "请输入验证码", trigger: "blur"}
        ]
      },
      register_page: false,
      register_form: {
        phone: '',
        password: '',
        vif: '',
        invitation_code: '',
      },
      register_rule: {
        phone: [
          {required: true, message: "请输入手机号码", trigger: "blur"}
        ],
        password: [
          {required: true, message: "请输入密码", trigger: "blur"}
        ],
        vif: [
          {required: true, message: "请输入验证码", trigger: "blur"}
        ]
      },
      menu_status: false,
      mobile_menu_status: false,
      ws: {},
      ws_close_manual: false, //是否手动关闭
      api_token: '',
    };
  },
  mounted() {
    //选中记录
    setInterval(() => {
      this.active = this.$router.currentRoute.path;
    }, 500);
    //开箱记录
    this.get_box_record();
    //邀请码
    let invitation_code = this.$route.query.invitation_code;
    if (invitation_code) {
      this.register_form.invitation_code = invitation_code;
    }

    //登录jwt
    let jwt_token = this.$route.query.jwt_token;
    if (jwt_token) {
      setStore({name: 'jwt_token', content: jwt_token});
      let newQuery = JSON.parse(JSON.stringify(this.$route.query)) // 先拷贝一个一模一样的对象
      delete newQuery.jwt_token //再删除jwt_token
      this.$router.replace({query: newQuery}) //再把新的替换了
    }
    
    this.api_token = getStore({name: 'jwt_token'});
    this.get_me();
    this.get_global();
  },
  destroyed() {
    this.ws_close_manual = true;
    this.ws.close();
  },
  methods: {
    abrasion, quality, quality2, color, repair, rand,

    //获取全局设置
    get_global() {
      ttGet("/api.php/common/get_global", '', (res) => {
        if (res.code == 200) {
          this.site = res.data.site;
          document.title = res.data.site.web_name;
          document.querySelector('meta[name="keywords"]').setAttribute('content', res.data.site.keywords);
          document.querySelector('meta[name="description"]').setAttribute('content', res.data.site.description);
          document.querySelector('link[rel="icon"]').setAttribute('href', this.server_url + '/public/uploads/' + res.data.site.ico);
        }
      });
    },

    //获取用户信息
    get_me() {
      let jwt_token = getStore({name: 'jwt_token'});
      if (jwt_token) {
        ttGet("/api.php/user/user_info", '', (res) => {
          if (res.code == 200) {
            this.user = res.data.user;
          } else {
            removeStore({name: 'jwt_token'});
          }
        });
      }
    },

    //游戏类型切换
    game_type_change() {
      let game_type = this.game_type;
      this.$emit('game_type_change', {game_type: game_type});
    },

    //电脑端点击
    menu() {
      this.menu_status = !this.menu_status;
    },

    //手机端点击
    mobile_menu() {
      this.mobile_menu_status = !this.mobile_menu_status;
    },

    //页面关闭
    page_close() {
      this.menu_status = false;
      this.mobile_menu_status = false;
    },

    //调用开箱记录
    get_box_record() {
      let profit_status = this.profit_status;
      this.ws = ttWebSocket('get_box_record', {profit_status: profit_status}, (res) => {
        this.box_record_status = true;
        if (res.code == 200) {
          let new_max = res.data.new_max;
          new_max.move = false;
          this.new_max = new_max;
          let new_list = this.box_record.concat(res.data.list);
          let length = new_list.length;
          for (let x in new_list) {
            let index = length - (length + Number(x));
            new_list[x].order = index;
            new_list[x].move = false;
          }
          this.box_record = new_list;
          setTimeout(() => {
            if (length > 35) {
              new_list.splice(0, length - 35);
              for (let x in new_list) {
                let index = length - (length + Number(x));
                new_list[x].order = index;
              }
              this.box_record = new_list;
            }
          }, 500);
        } else {
          this.$message({
            message: res.msg,
            type: 'warning'
          });
        }
      }, (res) => {
        res(this.ws_close_manual)
      });
    },

    //切换开箱记录类型
    box_record_profit(profit_status) {
      // this.ws_close_manual = true;
      // this.ws.close();
      this.profit_status = profit_status;
      this.box_record_status = false;
      // this.box_record = [];
      // this.get_box_record();
    },

    //账号登录
    user_login() {
      this.login_form = {
        phone: '',
        password: '',
      };
      this.login_page = true;
      this.forgot_page = false;
      this.register_page = false;
    },

    //登录
    login() {
      this.$refs.login_form.validate(valid => {
        if (valid) {
          let login_form = this.login_form;
          let loading = this.$loading({
            spinner: 'el-icon-loading',
            background: 'rgba(0, 0, 0, 0.7)'
          });
          ttPost("/api.php/login/login", {
            phone: login_form.phone,
            password: login_form.password,
          }, (res) => {
            loading.close();
            if (res.code == 200) {
              setStore({name: 'jwt_token', content: res.data.jwt_token});
              this.get_me();
              this.login_page = false;
              this.$message({
                message: res.msg,
                type: 'success'
              });
              location.reload();
            } else {
              this.$message({
                message: res.msg,
                type: 'warning'
              });
            }
          });
        }
      });
    },

    //用户忘记密码
    user_forgot() {
      this.forgot_form = {
        phone: '',
        password: '',
        vif: '',
      };
      this.login_page = false;
      this.forgot_page = true;
    },

    //用户忘记密码获取验证码
    get_vif2() {
      let phone = this.forgot_form.phone
      if (!phone) {
        return this.$message({
          message: '请输入手机号码',
          type: 'warning'
        });
      }
      let loading = this.$loading({
        spinner: 'el-icon-loading',
        background: 'rgba(0, 0, 0, 0.7)'
      });
      ttPost("/api.php/login/get_vif2", {phone: phone}, (res) => {
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

    //重置密码
    forgot() {
      this.$refs.forgot_form.validate(valid => {
        if (valid) {
          let forgot_form = this.forgot_form;
          let loading = this.$loading({
            spinner: 'el-icon-loading',
            background: 'rgba(0, 0, 0, 0.7)'
          });
          ttPost("/api.php/login/forgot", {
            phone: forgot_form.phone,
            password: forgot_form.password,
            vif: forgot_form.vif
          }, (res) => {
            loading.close();
            if (res.code == 200) {
              this.user_login();
              this.$message({
                message: res.msg,
                type: 'success',
                customClass: 'hierarchy'
              });
            } else {
              this.$message({
                message: res.msg,
                type: 'warning'
              });
            }
          });
        }
      });
    },

    //用户注册
    user_register() {
      this.register_form = {
        phone: '',
        password: '',
        vif: '',
      };
      this.login_page = false;
      this.register_page = true;
    },

    //用户注册获取验证码
    get_vif() {
      let phone = this.register_form.phone
      if (!phone) {
        return this.$message({
          message: '请输入手机号码',
          type: 'warning'
        });
      }
      let loading = this.$loading({
        spinner: 'el-icon-loading',
        background: 'rgba(0, 0, 0, 0.7)'
      });
      ttPost("/api.php/login/get_vif", {phone: phone}, (res) => {
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

    //注册账号
    register() {
      this.$refs.register_form.validate(valid => {
        if (valid) {
          let register_form = this.register_form;
          let loading = this.$loading({
            spinner: 'el-icon-loading',
            background: 'rgba(0, 0, 0, 0.7)'
          });
          ttPost("/api.php/login/register", {
            phone: register_form.phone,
            password: register_form.password,
            vif: register_form.vif,
            invitation_code: register_form.invitation_code,
          }, (res) => {
            loading.close();
            if (res.code == 200) {
              setStore({name: 'jwt_token', content: res.data.jwt_token});
              this.get_me();
              this.register_page = false;
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
        }
      });
    },

    //steam账号登录
    steam_login() {
      let loading = this.$loading({
        spinner: 'el-icon-loading',
        background: 'rgba(0, 0, 0, 0.7)'
      });
      ttPost("/api.php/login/steam_login", '', (res) => {
        loading.close();
        if (res.code == 200) {
          this.login_page = false;
          window.location.href = res.data.auth_url;
        } else {
          this.$message({
            message: res.msg,
            type: 'warning'
          });
        }
      });
    },

    //退出登录
    log_out() {
      removeStore({name: 'jwt_token'});
      this.user = {};
      this.menu_status = false;
      this.mobile_menu_status = false;
    },

    //打开充值页面
    recharge() {
      this.recharge_page = true;
    },

    //去充值
    to_recharge() {
      let recharge_num = this.recharge_num;
      if (!recharge_num) {
        return this.$message({
          message: '请输入充值金额!',
          type: 'warning'
        });
      }
      let loading = this.$loading({
        spinner: 'el-icon-loading',
        background: 'rgba(0, 0, 0, 0.7)'
      });
      ttPost("/api.php/user/to_recharge", {
        recharge_num: recharge_num,
        type: this.recharge_type
      }, (res) => {
        loading.close();
        if (res.code == 200) {
          let pay_type = res.data.pay_type;
          let order = res.data.order;
          if (pay_type == 1) {
            let jumpUrl = res.data.jumpUrl;
            window.location.href = jumpUrl;
          } else if (pay_type == 2) {
            this.recharge_page = false;
            this.recharge_page2 = true;
            this.pay_img = res.data.pay_img;
            let timer = setInterval(function () {
              ttPost("/api.php/user/pay_query", {order: order}, (res) => {
                if (res.code == 200) {
                  clearInterval(timer);
                  this.user.bean = res.data.balance;
                  this.recharge_page2 = false;
                  this.$message({
                    message: res.msg,
                    type: 'success'
                  });
                }
              });
            }, 5000);
          }
        } else {
          this.$message({
            message: res.msg,
            type: 'warning'
          });
        }
      });
    },

    //cdk充值
    to_cdk() {
      let cdk = this.cdk;
      if (!cdk) {
        return this.$message({
          message: '请输入cdk!',
          type: 'warning'
        });
      }
      let loading = this.$loading({
        spinner: 'el-icon-loading',
        background: 'rgba(0, 0, 0, 0.7)'
      });
      ttPost("/api.php/user/to_cdk", {cdk: cdk}, (res) => {
        loading.close();
        if (res.code == 200) {
          this.user.bean = res.data.balance;
          this.recharge_page = false;
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

    // 测试接口跳转
    testApi() {
      let jwt_token = getStore({name: 'jwt_token'});
      alert(jwt_token);
    }
  }
}
</script>

<style lang="scss" scoped>
.header {
  background-color: #1b1c24;
}
.header-account__refill-btn-1{
    display: block;
    margin-right: 20px;
    width: auto;
    height: auto;
    padding: 8px 8px 8px 8px;
    border-radius: 5px;
   background-color: #ED1C24;
  }
  .header__top-menu-item1{
    border-radius: 5px;
   background-color: #ED1C24;
  }
.live {
  padding-top: 2px;
  padding-left: 2px;
}

.live__list-wrapper {
  position: relative;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  overflow: hidden;
}

.live__buttons {
  margin-top: -2px;
  padding-right: 2px;
}

.live__btn-wrapper {
  height: 50%;
  padding-top: 2px;
}

.live__btn {
  position: relative;
  display: block;
  width: 100%;
  height: 100%;
  padding-right: 43px;
  padding-left: 24px;
  background-color: #21222b;
  border-radius: 3px;

  &::before {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: -webkit-gradient(linear, left top, right top, from(#272831), to(#3d2f37));
    background: linear-gradient(90deg, #272831, #3d2f37);
    border-radius: 3px;
    visibility: hidden;
    opacity: 0;
    -webkit-transition: opacity .3s ease, visibility .3s ease;
    transition: opacity .3s ease, visibility .3s ease;
    content: "";
  }
}

.live__btn--active {
  cursor: default;

  &::before {
    visibility: visible;
    opacity: 1;
  }
}

.live__btn-text {
  position: relative;
  display: block;
  color: #5f616f;
  font-weight: 700;
  font-size: 10px;
  text-transform: uppercase;
  -webkit-transition: color .3s ease;
  transition: color .3s ease;
}

.live__btn--active .live__btn-text, .live__btn--active:hover .live__btn-text {
  color: #f2ae6f;
}

.live__btn-icon {
  position: absolute;
  top: 50%;
  right: 14px;
  margin-top: -11px;
}

.live__btn--top .live__btn-icon {
  margin-top: -12px;
}

.live__list {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
}

.live__item {
  position: relative;
  -ms-flex-negative: 0;
  flex-shrink: 0;
  list-style-type: none;
}

.live__item + .live__item:not(:last-child) {
  margin-left: 2px;
}

.drop-in {
  -webkit-animation: drop-in-data-v-c1289f9a .5s ease-in-out normal forwards;
  animation: drop-in-data-v-c1289f9a .5s ease-in-out normal forwards;
}

@-webkit-keyframes drop-in-data-v-c1289f9a {
  0% {
    max-width: 0
  }
  to {
    max-width: 160px
  }
}

@keyframes drop-in-data-v-c1289f9a {
  0% {
    max-width: 0
  }
  to {
    max-width: 160px
  }
}

.live__link {
  position: relative;
  display: block;
  overflow: hidden;
  background: radial-gradient(101.95% 157% at 50% -23%, rgba(191, 198, 201, .4) 0, rgba(169, 175, 176, .316) 31.63%, rgba(67, 75, 77, 0) 100%), #21222b;
  border-radius: 3px;
}

.live__link::before {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 66px;
  height: 66px;
  margin-top: -43px;
  margin-left: -33px;
  background: url("~@/assets/img/g-dark.6bc42fba.svg") no-repeat;
  content: "";
}

.load.live__link::before {
  background: none;
}

.live__front {
  -webkit-transition: -webkit-transform .5s cubic-bezier(.28, .67, .04, 1);
  transition: -webkit-transform .5s cubic-bezier(.28, .67, .04, 1);
  transition: transform .5s cubic-bezier(.28, .67, .04, 1);
  transition: transform .5s cubic-bezier(.28, .67, .04, 1), -webkit-transform .5s cubic-bezier(.28, .67, .04, 1);
}

.live__back {
  position: absolute;
  top: 100%;
  left: 0;
  width: 100%;
  visibility: hidden;
  opacity: 0;
  -webkit-transition: opacity .5s ease, visibility .5s ease, -webkit-transform .5s cubic-bezier(.28, .67, .04, 1);
  transition: opacity .5s ease, visibility .5s ease, -webkit-transform .5s cubic-bezier(.28, .67, .04, 1);
  transition: transform .5s cubic-bezier(.28, .67, .04, 1), opacity .5s ease, visibility .5s ease;
  transition: transform .5s cubic-bezier(.28, .67, .04, 1), opacity .5s ease, visibility .5s ease, -webkit-transform .5s cubic-bezier(.28, .67, .04, 1);
}

.live__link:hover .live__back, .live__link:hover .live__front {
  -webkit-transform: translateY(-100%);
  transform: translateY(-100%);
}

.live__link:hover .live__back {
  visibility: visible;
  opacity: 1;
}

.live__back, .live__front {
  height: 100%;
}

.battle-icon {
  position: absolute;
  top: 7px;
  left: 13px;
  width: 18px;
  height: 18px;
  background: url("~@/assets/img/upgrade.04edd704.svg") no-repeat;
  color: #f7dd73;
  background-size: 18px;
  background-position: 50%;
  border-radius: 4px;
  z-index: 3;
}

.item__weapon-tag {
  font-size: 10px;
  position: absolute;
  right: 14px;
  top: 8px;
  color: #f9a328;
}

.live__image-wrapper {
  position: absolute;
  top: -10px;
  left: 0;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  width: 100%;
  height: 100%;
  line-height: 1;
}

.live__image {
  -webkit-filter: drop-shadow(0 10px 3px rgba(0, 0, 0, .25));
  filter: drop-shadow(0 10px 3px rgba(0, 0, 0, .25));
}

.live__footer {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  padding: 14px;
}

.item__skin-name, .item__weapon-name {
  position: relative;
  z-index: 2;
  display: block;
  overflow: hidden;
  font-size: 13px;
  white-space: nowrap;
  text-overflow: ellipsis;
}

.live__subtitle {
  color: #e2e3e7;
  font-weight: 300;
  font-size: 11px;
  line-height: 1.6;
}

.item__weapon-name {
  color: #b1b1b1;
  font-weight: 300;
  font-size: 11px;
  line-height: 1.6;
}

.live__title {
  overflow: hidden;
  color: #e2e3e7;
  font-size: 12px;
  font-weight: 700;
  white-space: nowrap;
  text-overflow: ellipsis;
  text-align: left;
}


.live__back {
  position: absolute;
  top: 100%;
  left: 0;
  width: 100%;
  visibility: hidden;
  opacity: 0;
  -webkit-transition: opacity .5s ease, visibility .5s ease, -webkit-transform .5s cubic-bezier(.28, .67, .04, 1);
  transition: opacity .5s ease, visibility .5s ease, -webkit-transform .5s cubic-bezier(.28, .67, .04, 1);
  transition: transform .5s cubic-bezier(.28, .67, .04, 1), opacity .5s ease, visibility .5s ease;
  transition: transform .5s cubic-bezier(.28, .67, .04, 1), opacity .5s ease, visibility .5s ease, -webkit-transform .5s cubic-bezier(.28, .67, .04, 1);
}

.live__back, .live__front {
  height: 100%;
}

.live__back .live__image-case-wrapper {
  position: absolute;
  right: 0;
  bottom: 10px;
  -webkit-transform: translateY(30px);
  transform: translateY(30px);
  -webkit-transition: -webkit-transform .7s ease;
  transition: -webkit-transform .7s ease;
  transition: transform .7s ease;
  transition: transform .7s ease, -webkit-transform .7s ease;
}

.live__link:hover .live__image-case-wrapper {
  -webkit-transform: none;
  transform: none;
}

.live__back .live__image-case-wrapper .live__image-case {
  height: 135px;
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
}

.live__image-case {
  height: 135px;
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
}

.live__footer {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  padding: 14px;
}

.live__back .live__user-photo-wrapper {
  margin-bottom: 12px;
  -webkit-transform: translateY(10px);
  transform: translateY(10px);
  -webkit-transition: -webkit-transform .7s ease;
  transition: -webkit-transform .7s ease;
  transition: transform .7s ease;
  transition: transform .7s ease, -webkit-transform .7s ease;
  text-align: left;
}

.live__user-photo-wrapper {
  margin-bottom: 7px;
  -webkit-transform: translateY(10px);
  transform: translateY(10px);
  -webkit-transition: -webkit-transform .7s ease;
  transition: -webkit-transform .7s ease;
  transition: transform .7s ease;
  transition: transform .7s ease, -webkit-transform .7s ease;
}

.live__back .live__user-photo-wrapper .live__user-photo {
  width: 36px;
  height: 36px;
  border-radius: 36px;
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
}

.live__user-photo {
  width: 36px;
  height: 36px;
  border-radius: 36px;
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
}

.live__title {
  overflow: hidden;
  color: #e2e3e7;
  font-size: 12px;
  font-weight: 700;
  white-space: nowrap;
  text-overflow: ellipsis;
  text-align: left;
}

.loading-skeleton {
  position: absolute;
  top: 45%;
  left: 50%;
  transform: translateX(-50%);
  width: 50px;
  height: 35px;
  background-color: rgba(0, 0, 0, .2);
  background-image: -webkit-gradient(linear, left top, right top, from(rgba(0, 0, 0, .2)), color-stop(rgba(0, 0, 0, .2)), to(rgba(0, 0, 0, .2)));
  background-image: linear-gradient(90deg, rgba(0, 0, 0, .2), rgba(0, 0, 0, .2), rgba(0, 0, 0, .2));
  background-size: 200px 100%;
  background-repeat: no-repeat;
  border-radius: 4px;
  display: inline-block;
  line-height: 1;
  -webkit-animation: skeleton-animate-wave-data-v-95f82aca 1.2s ease-in-out 0s infinite normal none running;
  animation: skeleton-animate-wave-data-v-95f82aca 1.2s ease-in-out 0s infinite normal none running
}

.loading-skeleton2 {
  position: absolute;
  top: 80%;
  left: 50%;
  transform: translateX(-50%);
  width: 120px;
  height: 20px;
  background-color: rgba(0, 0, 0, .2);
  background-image: -webkit-gradient(linear, left top, right top, from(rgba(0, 0, 0, .2)), color-stop(rgba(0, 0, 0, .2)), to(rgba(0, 0, 0, .2)));
  background-image: linear-gradient(90deg, rgba(0, 0, 0, .2), rgba(0, 0, 0, .2), rgba(0, 0, 0, .2));
  background-size: 200px 100%;
  background-repeat: no-repeat;
  border-radius: 4px;
  display: inline-block;
  line-height: 1;
  -webkit-animation: skeleton-animate-wave-data-v-95f82aca 1.2s ease-in-out 0s infinite normal none running;
  animation: skeleton-animate-wave-data-v-95f82aca 1.2s ease-in-out 0s infinite normal none running
}

@-webkit-keyframes skeleton-animate-wave-data-v-95f82aca {
  0% {
    background-position: -200px 0
  }
  to {
    background-position: calc(200px + 100%) 0
  }
}

@keyframes skeleton-animate-wave-data-v-95f82aca {
  0% {
    background-position: -200px 0
  }
  to {
    background-position: calc(200px + 100%) 0
  }
}

.live__list-wrapper:after {
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  z-index: 1;
  width: 160px;
  background: -webkit-gradient(linear, right top, left top, color-stop(11.88%, rgba(27, 28, 36, .9)), color-stop(81.88%, rgba(27, 28, 36, 0)));
  background: linear-gradient(270deg, rgba(27, 28, 36, .9) 11.88%, rgba(27, 28, 36, 0) 81.88%);
  content: "";
}

.header__top-wrapper {
  position: relative;
}

.header__top {
  background-color: #1b1c24;
}

.header__top-inner {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
}

.header__logo-wrapper {
  -ms-flex-negative: 0;
  flex-shrink: 0;
  margin-right: 22px;
}

.header__logo-link {
  display: block;
  //padding: 22px 0;
  -webkit-transition: opacity .3s ease;
  transition: opacity .3s ease;
}

.header__logo-link:hover {
  opacity: .7;
}

.header__logo-image {
  width: 210px;
  height: 95px;
  background: url("~@/assets/img/logo.png") no-repeat;
  background-size: 100% 100%;
}

.type {
  width: 100px;
}

.header__online-counter {
  position: relative;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  margin-left: 30px;
}

.header__lang, .header__online-counter {
  -ms-flex-negative: 0;
  flex-shrink: 0;
}

.header__online-icon {
  -ms-flex-negative: 0;
  flex-shrink: 0;
  width: 32px;
  height: 32px;
  margin-right: 6px;
  -webkit-animation: liveAnimation 1.5s infinite;
  animation: liveAnimation 1.5s infinite;
}

.header__online-cnt {
  color: #46b975;
  font-weight: 700;
  font-size: 15px;
}

.header__online-label {
  color: #95a498;
  font-weight: 300;
  font-size: 9px;
  text-transform: uppercase;
}

@-webkit-keyframes liveAnimation {
  0% {
    opacity: .3
  }
  50% {
    opacity: 1
  }
  to {
    opacity: .3
  }
}

@keyframes liveAnimation {
  0% {
    opacity: .3
  }
  50% {
    opacity: 1
  }
  to {
    opacity: .3
  }
}

.header__top-menu {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-flex: 1;
  -ms-flex-positive: 1;
  flex-grow: 1;
  flex-shrink: 0;
  margin-left: 30px;
}

.header__top-menu-item {
  -ms-flex-negative: 0;
  flex-shrink: 0;
}

.header__top-menu-link {
  position: relative;
  display: block;
  padding: 10px 0;
  color: #cecfd6;
  font-weight: 300;
  font-size: 14px;
  text-transform: uppercase;
  -webkit-transition: color .3s ease;
  transition: color .3s ease;
}

.header__top-menu-link:hover {
  color: #fff;
}

.header__top-menu-link--faq {
  padding-right: 26px;
}

.header__top-menu-icon {
  position: absolute;
  top: 50%;
  right: 0;
  margin-top: -10px;
}

.header__top-menu-item + .header__top-menu-item {
  margin-left: 18px;
}

.auth {
  -ms-flex-negative: 0;
  flex-shrink: 0;
}

.header-account {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -ms-flex-negative: 0;
  flex-shrink: 0;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
}

.header-account__refill-wrapper {
  -ms-flex-negative: 0;
  flex-shrink: 0;
  margin-right: 26px;
}

.header-account__refill-btn {
  position: relative;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  width: 40px;
  height: 34px;
  color: #4f3d2e;
  font-size: 0;
  background-color: #f3a45d;
  border-radius: 8px;
  -webkit-transition: color .3s ease, background-color .3s ease;
  transition: color .3s ease, background-color .3s ease;
}

.header-account__refill-btn:after {
  position: absolute;
  top: 7px;
  right: -13px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  width: 20px;
  height: 20px;
  color: #ffe5cd;
  font-weight: 700;
  font-size: 18px;
  line-height: 0;
  background: -webkit-gradient(linear, left top, right top, color-stop(-2.5%, #e6a160), color-stop(31.01%, #bd8a5a), color-stop(55.76%, #6c5645), to(#5c4c40));
  background: linear-gradient(90deg, #e6a160 -2.5%, #bd8a5a 31.01%, #6c5645 55.76%, #5c4c40);
  border-radius: 10px;
  content: "+";
}

.header-account__link {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -ms-flex-negative: 0;
  flex-shrink: 0;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  color: #fff;
  font-weight: 700;
  font-size: 15px;
  -webkit-transition: opacity .3s ease;
  transition: opacity .3s ease;
}

.header-account__balance {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  -ms-flex-negative: 0;
  flex-shrink: 0;
  -webkit-box-align: end;
  -ms-flex-align: end;
  align-items: flex-end;
}

.header-account__balance2 {
  display: none;;
}

.header-account__username {
  max-width: 95px;
  margin-bottom: 2px;
  overflow: hidden;
  color: #aea49a;
  font-size: 10px;
  white-space: nowrap;
  text-transform: uppercase;
  text-overflow: ellipsis;
}

.header-account__photo-wrapper {
  -ms-flex-negative: 0;
  flex-shrink: 0;
  margin-left: 16px;
}

.header-account__photo {
  border-radius: 10px;
}

.header-account__menu {
  -ms-flex-negative: 0;
  flex-shrink: 0;
}

.account-menu {
  position: relative;
  z-index: 1010;
}

.account-menu__btn {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  padding: 9px 6px;
  -webkit-user-select: none;
  -ms-user-select: none;
  -moz-user-select: none;
  user-select: none;
  -ms-touch-action: manipulation;
  touch-action: manipulation;
  background: transparent;
  margin-left: 10px;
}

.window-overlay {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1040;
  width: 100%;
  height: 100%;
  overflow: hidden;
  background-color: rgba(0, 0, 0, .7);
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  -webkit-transition: background-color .2s ease;
  transition: background-color .2s ease;
}

.account-menu__content {
  position: absolute;
  top: 55px;
  right: -7px;
  min-width: 490px;
  padding: 26px 26px 20px;
  background-color: rgba(23, 23, 30, .75);
  border-radius: 15px;
  -webkit-box-shadow: 0 19px 74px rgb(8 8 12 / 80%);
  box-shadow: 0 19px 74px rgb(8 8 12 / 80%);
  -webkit-backdrop-filter: blur(5px);
  backdrop-filter: blur(5px);
}

.account-menu__content-menu {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  margin: -5px -5px 10px;
}

.account-menu__content-item {
  -ms-flex-negative: 0;
  flex-shrink: 0;
  width: 50%;
  padding: 5px;
}

.account-menu__content:after {
  position: absolute;
  top: -8px;
  right: 18px;
  width: 0;
  height: 0;
  border-right: 9px solid transparent;
  border-bottom: 8px solid rgba(23, 23, 30, .75);
  border-left: 9px solid transparent;
  content: "";
}

.account-menu__content-link {
  position: relative;
  display: block;
  width: 100%;
  min-height: 170px;
  overflow: hidden;
  color: #aeaeb7;
  background-color: #1d1d25;
  border-radius: 10px;
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
}

.account-menu__content-link:before {
  position: absolute;
  right: 0;
  bottom: 0;
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -webkit-transition: -webkit-transform .3s ease;
  transition: -webkit-transform .3s ease;
  transition: transform .3s ease;
  transition: transform .3s ease, -webkit-transform .3s ease;
  content: "";
}

.account-menu__content-link--profile:before {
  width: 118px;
  height: 132px;
  background: url("~@/assets/img/profile-menu.1cf6d70d.webp") 100%/100% no-repeat;
}

.account-menu__content-link--refill:before {
  width: 172px;
  height: 132px;
  background: url("~@/assets/img/refill-menu.b1da7aed.webp") 100%/100% no-repeat;
}

.account-menu__content-link--refer:before {
  width: 172px;
  height: 132px;
  background: url("~@/assets/img/refer1.d60cea20.png") 100%/100% no-repeat;
}

.account-menu__content-link--partner-history:before {
  width: 172px;
  height: 132px;
  background: url("~@/assets/img/advantage-5.cd98d68d.webp") 100%/100% no-repeat;
  bottom: -20px !important;
}

.account-menu__content-additional {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  padding: 0 5px;
}

.account-menu__agreement-wrapper, .account-menu__logout-wrapper {
  -ms-flex-negative: 0;
  flex-shrink: 0;
}

.account-menu__logout-btn {
  position: relative;
  padding: 5px 18px 5px 15px;
  color: #474752;
  font-size: 12px;
  text-transform: uppercase;
  -webkit-transition: color .3s ease, opacity .3s ease;
  transition: color .3s ease, opacity .3s ease;
  background: transparent;
}

.account-menu__link-label {
  position: absolute;
  top: 0;
  left: 0;
  padding: 22px;
  font-weight: 300;
  font-size: 14px;
  text-transform: uppercase;
}

.auth__btn {
  position: relative;
  display: block;
  padding: 15px 59px 15px 26px;
  color: #392c22;
  font-weight: 900;
  font-size: 14px;
  text-transform: uppercase;
  background-color: #e69d5d;
  border-radius: 7px;
  -webkit-transition: color .3s ease, background-color .3s ease, opacity .3s ease;
  transition: color .3s ease, background-color .3s ease, opacity .3s ease;
}

.auth__text {
  height: 16px;
}

.i-btn {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -ms-flex-negative: 0;
  flex-shrink: 0;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  color: #272735;
  font-weight: 700;
  text-align: center;
  border-radius: 8px;
  -webkit-transition: all .3s ease;
  transition: all .3s ease;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

.i-btn.i-btn--sm {
  min-width: 60px;
  height: 40px;
  border-radius: 4px;
  padding: 0 15px;
  font-size: 12px;
  border-width: 1px;
}

.primary.i-btn {
  background-color: #e59d5d;
}

.primary.i-btn:hover:not(:disabled) {
  color: #684933;
  background-color: #fac26f;
}

.svg-inline--fa {
  display: var(--fa-display, inline-block);
  height: 1em;
  overflow: visible;
  vertical-align: -0.125em;
}

.mobile-menu {
  position: relative;
  z-index: 1010;
  display: none;
}

.mobile-menu__btn {
  background-color: transparent;
}

.mobile-menu__content {
  position: absolute;
  top: 45px;
  right: -4px;
  width: 295px;
  background-color: rgba(17, 17, 23, .98);
  border-radius: 6px;
  -webkit-box-shadow: 0 9px 34px rgb(8 8 12 / 20%);
  box-shadow: 0 9px 34px rgb(8 8 12 / 20%);
}

.mobile-menu__list {
  padding: 10px 12px;
}

.text-left {
  text-align: left !important;
}
@media (max-width: 768px) {
  /* 手机端样式 */
  /* 在此添加针对手机端的CSS样式 */
  .text-center {
  position: relative;
  height: auto; /* 设置容器高度为屏幕高度的100% */
}

.el-carousel__item img {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%) scale(0.9);
  transition: all 0.3s ease-out;
  width: 100%; /* 设置图片宽度为容器宽度的100% */
}

.el-carousel__item.is-active img {
  transform: translate(-50%, -50%) scale(1);
}

}

@media (min-width: 769px) {
  /* 电脑端样式 */
  /* 在此添加针对电脑端的CSS样式 */
  .text-center {
  position: relative;
  height: auto;
}

.el-carousel__item img {
  width: 80%; /* 缩小为原来的80% */
  height: auto; /* 高度自适应 */
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%) scale(1);
  transition: all 0.3s ease-out;
}

.el-carousel__item.is-active img {
  transform: translate(-50%, -50%) scale(0.6);
}


}

.mobile-menu__content:after {
  position: absolute;
  top: -6px;
  right: 11px;
  width: 0;
  height: 0;
  border-right: 7px solid transparent;
  border-bottom: 6px solid rgba(17, 17, 23, .98);
  border-left: 7px solid transparent;
  content: "";
}

.mobile-menu__item-link {
  position: relative;
  display: block;
  padding: 11px 15px 10px 28px;
  color: #fff;
  font-size: 13px;
  line-height: 1;
  -webkit-transition: none;
  transition: none;
}

.mobile-menu__item-link--logout {
  color: #868699;
}

.mobile-menu__item-link {
  position: relative;
  display: block;
  padding: 11px 15px 10px 28px;
  color: #fff;
  font-size: 13px;
  line-height: 1;
  -webkit-transition: none;
  transition: none;
  background: transparent;
}

.recharge-dialog {
  > > > {
    .el-dialog {
      max-width: 560px;
      margin: 0 auto;
      position: relative;
      background-color: #17171f;
      border-radius: 10px;
    }

    .el-dialog__header {
      padding: 33px 39px 20px;
      background-color: #1b1c24;
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
    }

    .el-dialog__title {
      font-size: 21px;
      font-weight: 500;
      margin: 0;
      color: #fff;
    }

    .el-dialog__body {
      padding: 1.5rem !important;
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
      border-bottom: 1px solid #e59d5d;
      background-color: transparent;
      box-shadow: none;
    }

    button, input, select, textarea {
      color: inherit;
      background-color: transparent;
      border-style: none;
    }

    .recharge-btn {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      -ms-flex-negative: 0;
      flex-shrink: 0;
      -webkit-box-align: center;
      -ms-flex-align: center;
      align-items: center;
      -webkit-box-pack: center;
      -ms-flex-pack: center;
      justify-content: center;
      color: #272735;
      font-weight: 700;
      text-align: center;
      border-radius: 8px;
      -webkit-transition: all .3s ease;
      transition: all .3s ease;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
      min-width: 80px;
      height: 48px;
      border-radius: 4px;
      padding: 0 15px;
      font-size: 14px;
      border-width: 1px;
      background-color: #e59d5d !important;
      margin-top: 20px;
    }

    .input-item {
      width: 100%;
      position: relative;
      background: #dcdcdc;
      border-radius: 8px;
    }

    .input-item + .input-item {
      margin-top: 20px;
    }

    .recharge-input2 {
      width: 100%;
      height: 100%;
      padding: 10px 20px;
      border: 1px solid transparent;
      color: #353535;
      caret-color: #353535;
      font-size: 14px;
      font-weight: 700;
      border-radius: 8px;
      background-color: transparent;

    }

    .recharge-btn3 {
      width: 100%;
      max-width: inherit;
      margin: 0;
    }

    .steam-login {
      cursor: pointer;
      margin: 10px;
    }

    .steam-login-txt {
      color: #dfdfdf;
      margin-top: 5px;
    }

    .login-tips {
      color: #7e7e7e;
    }

    .el-input {
      width: 100%;
      position: relative;
      background: #252530;
      height: 48px;
      border-radius: 8px;
    }

    .el-input__inner {
      width: 100%;
      height: 100%;
      padding: 10px 20px;
      border: 1px solid transparent;
      color: #c2c7d7;
      caret-color: #c2c7d7;
      font-size: 13px;
      border-radius: 8px;
      -webkit-transition: background-color .3s ease;
      transition: background-color .3s ease;
      text-align: left;
    }

    .el-input-group__append {
      color: #e69d5d;
      border: 1px solid #2e2925;
      border-radius: 8px;
      background-color: transparent;
    }
  }
}

.pay-img {
  > > > {
    .el-dialog {
      max-width: 350px;
    }
  }
}

.login-option {
  display: flex;
  justify-content: space-between;
  margin-top: 20px;
  color: #dfdfdf;
  cursor: pointer;
}

.header__bottom {
  height: 64px;
}

.header__bottom-inner {
  position: relative;
  z-index: 5;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  padding: 5px;
  background-color: rgba(20, 20, 28, .85);
  border-radius: 12px;
  -webkit-backdrop-filter: blur(6px);
  backdrop-filter: blur(6px);
}

.header__bonus-menu {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -ms-flex-negative: 0;
  flex-shrink: 0;
}

.header__bonus-menu-link {
  position: relative;
  display: block;
  width: 190px;
  height: 74px;
  overflow: hidden;
  color: #9ea0ab;
  font-weight: 300;
  font-size: 12px;
  text-transform: uppercase;
  background: linear-gradient(75.97deg, #1b1c24 36.23%, #22232d 99.69%);
  border-radius: 8px;
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -webkit-transition: opacity .3s ease;
  transition: opacity .3s ease;

  &::before {
    position: absolute;
    bottom: 0;
    left: 0;
    z-index: 1;
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    content: "";
    width: 126px;
    height: 74px;
    background: url("~@/assets/img/bonuses-icon.45adc167.webp") 100%/100% no-repeat;
  }

  &::after {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(75.97deg, #1d1f2b 36.23%, #313241 99.69%);
    border-radius: 8px;
    opacity: 0;
    -webkit-transition: opacity .3s ease;
    transition: opacity .3s ease;
    content: "";
  }
}

.header__bonus-menu-link--wheel {
  &::before {
    width: 126px;
    height: 74px;
    background: url("~@/assets/img/wheel-icon.2591a4ed.webp") 100%/100% no-repeat;
  }
}

.header__bonus-menu-link--giveaway {
  &::before {
    width: 100%;
    height: 100%;
    background-size: cover;
    background: url("~@/assets/img/giveaway-menu.c6539acf.webp") 100%/100% no-repeat;
    -webkit-transition: -webkit-transform .3s ease;
    transition: -webkit-transform .3s ease;
    transition: transform .3s ease;
    transition: transform .3s ease, -webkit-transform .3s ease;
  }
}

.header__bonus-menu-text {
  position: absolute;
  top: 13px;
  right: 15px;
  z-index: 1;
  width: 150px;
  overflow: hidden;
  white-space: nowrap;
  text-align: right;
  text-overflow: ellipsis;
}

.header__bonus-menu-item + .header__bonus-menu-item {
  margin-left: 5px;
}

.header__bonus-menu-link--giveaway .header__bonus-menu-text {
  color: #ffeac2;
  font-weight: 800;
}

.header__bonus-menu-link:hover {
  color: #9ea0ab;
}

.header__bonus-menu-link:hover:after {
  opacity: 1;
}

@media (min-width: 480px) {
}

@media (min-width: 768px) {
}

@media (min-width: 992px) {
}

@media (min-width: 1200px) {
  .live__link {
    width: 160px;
    height: 152px;
  }
  .header-account__refill-btn-1{
    display: none;
  }
  .live__image {
    width: 110px;
    height: 82px;
  }

  .account-menu__content {
    min-width: 440px;
    padding: 20px 20px 15px;
  }

  .account-menu__content-link {
    min-height: 140px;
  }

  .account-menu__content-link--profile:before {
    width: 100px;
    height: 112px;
  }

  .account-menu__link-label {
    padding: 18px;
    font-size: 13px;
  }

  .account-menu__content-link--refill:before {
    width: 146px;
    height: 112px;
  }

  .account-menu__link-label {
    padding: 18px;
    font-size: 13px;
  }
}

.header__menu {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-flex: 1;
  -ms-flex-positive: 1;
  flex-grow: 1;
  margin: -5px 0 -5px 10px;
}

.header__menu-item {
  border-right: 1px solid #1c1d25;
}

.header__menu-item-link {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  height: 100%;
  padding: 0 18px;
  color: #fff;
  font-size: 15px;
  -webkit-transition: color .3s ease;
  transition: color .3s ease;
}

.el-radio-group {
  >>> {
    .el-radio-button__orig-radio:checked+.el-radio-button__inner {
      color: #FFF !important;
      background: linear-gradient(292.1deg,#313340 .4%,#483c40 105.2%) !important;
      border: none !important;
    }

    .el-radio-button__inner:hover {
      color: #3a34b0 !important;
    }

    .el-radio-button__inner {
      background: #21212a !important;
      border: none !important;
      color: #c2c2c2 !important;
    }

    .el-radio-button__inner:hover {
      color: #fff !important;
    }

    .el-radio-button__orig-radio:checked+.el-radio-button__inner {
      color: #fff !important;
      background: linear-gradient(292.1deg,#313340 .4%,#483c40 105.2%) !important;
      border: none !important;
      box-shadow: none !important;
    }
  }
}

@media (min-width: 1440px) {
}

@media only screen and (max-width: 1439px) {
  .header__bonus-menu-link {
    width: 175px;
    height: 68px;
  }
  .header-account__refill-btn-1{
    display: none;
  }
  .container {
    padding: 0 25px;
  }
}

@media only screen and (max-width: 1199px) {
  .live__link {
    width: 140px;
    height: 90px;
  }
  .header-account__refill-btn-1{
    display: none;
  }
  .live__link:before {
    width: 54px;
    height: 54px;
    margin-top: -27px;
    margin-left: -27px;
  }

  .battle-icon, .item__weapon-tag {
    display: none;
  }

  .live__image-wrapper {
    top: 0;
  }

  .live__image {
    width: 90px;
    height: 67px;
  }

  .loading-skeleton {
    top: 30%;
  }

  .loading-skeleton2 {
    top: 72%;
  }

  .header__logo-image {
    width: 43px;
    background-image: url("~@/assets/img/logo2.png");
    background-size: contain;
    background-position: 50%;
  }

  .header__online-counter {
    margin-left: 20px;
  }

  .header__top-menu {
    margin-left: 20px;
  }

  .header__bonus-menu-link {
    width: 150px;
    height: 58px;
    font-size: 11px;
    border-radius: 6px;
  }
}

@media only screen and (max-width: 900px) {
  .live__buttons {
    display: none;
  }
  .header-account__refill-btn-1{
    display: none;
  }
  .live__link {
    width: 120px;
    height: 77px;
  }

  .live__link:before {
    width: 50px;
    height: 50px;
    margin-top: -25px;
    margin-left: -25px;
  }

  .live__image {
    width: 85px;
    height: 63px;
  }

  .loading-skeleton {
    top: 15%;
  }

  .loading-skeleton2 {
    top: 68%;
    width: 100px;
  }

  .header__logo-image {
    width: 33px;
    height: 32px;
  }

  .header__online-counter {
    -webkit-box-flex: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
    margin-left: 15px;
  }

  .header__online-icon {
    width: 25px;
    height: 25px;
  }

  .header__online-cnt {
    font-size: 12px;
  }

  .header__online-label {
    font-size: 9px;
  }

  .header__top-menu {
    display: none;
  }

  .auth {
    padding-right: 10px;
  }

  .mobile-menu {
    display: block;
  }

  .header__bottom {
    display: none;
  }

  .header-account__menu {
    display: none;
  }

  .header-account__refill-btn {
    width: 35px;
    height: 30px;
    border-radius: 6px;
  }

  .header-account__refill-btn {
    width: 35px;
    height: 30px;
    border-radius: 6px;
  }

  .header-account__refill-btn:after {
    display: none;
  }

  .header-account__username {
    margin-bottom: 1px;
    font-size: 9px;
  }

  .header-account__refill-icon {
    width: 18px;
    height: 18px;
  }

  .header-account__photo {
    width: 35px;
    height: 35px;
    border-radius: 6px;
  }
}

@media only screen and (max-width: 767px) {
  .header__logo-image {
    width: 29px;
    height: 28px;
  }
  .header-account__refill-btn-1{
    display: block;
    margin-right: 20px;
  }

  .header__online-icon {
    display: none;
  }

  .header__online-cnt {
    display: none;
  }

  .header__online-label {
    display: none;
  }

  .type {
    display: none;
  }

  .auth__text {
    display: none;
  }
}

@media only screen and (max-width: 559px) {
  .container {
    padding: 0 15px;
  }
  .header-account__refill-btn-1{
    display: block;
  }
  .header-account__refill-btn {
    width: auto;
    height: auto;
    padding: 8px 8px 8px 26px;
    font-weight: 700;
    font-size: 11px;
    border-radius: 5px;
  }

  .header-account__balance {
    display: none;
  }

  .header-account__balance2 {
    display: block;
  }

  .header-account__refill-wrapper {
    margin-right: 0;
  }

  .header-account__refill-icon {
    position: absolute;
    top: 50%;
    left: 5px;
    width: 16px;
    height: 16px;
    margin-top: -8px;
  }

  .header-account__balance {
    display: none;
  }
}
</style>
