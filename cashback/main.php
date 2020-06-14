<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>购物返现</title>

    <!-- load css -->
    <link href="./element-ui/theme-chalk/index.css" rel="stylesheet" type="text/css">
    <link href="./static/css/common.css" rel="stylesheet">
    <link href="./static/css/main.css" rel="stylesheet">

    <script type="text/javascript" src="./static/js/jquery.min.js"></script>
    <script type="text/javascript" src="./static/js/jquery.cookie.js"></script>
    <!-- 解决Promise未定义  -->
    <script type="text/javascript" src="./static/js/bluebird.min.js"></script>
    <script type="text/javascript" src="./static/js/api.js"></script>
    <script type="text/javascript" src="./static/js/utils.js"></script>
    <script type="text/javascript" src="./static/js/clipboard.min.js"></script>

    <!-- vue.js -->
    <script type="text/javascript" src="./static/js/vue.min.js"></script>
    <!-- element-ui -->
    <script type="text/javascript" src="./element-ui/index.js"></script>

    <script type="text/javascript">
      var uid = $.cookie('cookie_uid');
      if (!uid) { // 未登陆跳转到登录页面
        window.location.href = 'login.php';
      }
    </script>
  </head>
  <body>
    <div id="main" class="fill vcontainer main-content">
      <div class="fill vcontainer">
        <!-- 首页 -->
        <div class="fill vcontainer"
          v-loading="searching"
          element-loading-text="正在查询中......"
          v-show="activeIndex == 'home'">
          <div class="vcontainer search-header">
            <span style="text-align: left;">{{welcome}}</span>
            <span style="text-align: right;">购物返现</span>
          </div>
          <div class="hcontainer search-container">
            <el-input
              placeholder="请输入商品信息或订单号"
              prefix-icon="el-icon-search"
              clearable
              v-model="searchmsg">
            </el-input>
            <button class="search-btn" @click="searchClicked">查询</button>
          </div>
          <div class="vcontainer fill" v-show="showresult">
            <el-image class="search-imgurl" :src="picUrl" fit="fit" v-show="picUrl !=''"></el-image>
            <textarea class="search-result" v-model="searchresult" disabled="disabled"></textarea>
            <div class="hcontainer search-btns">
              <button
                class="search-copybtn"
                data-clipboard-action="copy"
                :data-clipboard-text="copymsg">
                复制内容
              </button>
              <button class="search-clearbtn" @click="searchresult=''">
                清空内容
              </button>
            </div>
            <el-link class="search-shorturl" type="primary" :href="shortUrl" target="_blank" v-show="shortUrl !=''">电脑链接</el-link>
            <span class="search-result-tip">{{tip}}</span>
          </div>
          <div v-show="!showresult" class="fill search-result-none"></div>
        </div>
        <!-- 个人中心 -->
        <div class="fill vcontainer mine-display" v-show="activeIndex == 'mine'">
          <div class="fill vcontainer" v-show="showmineitem">
            <div class="hcontainer mine-item-display" @click="itemClicked('余额')">
              <span>余额信息</span>
              <i class="el-icon-arrow-right"></i>
            </div>
            <div class="mine-item-blank"></div>
            <div class="hcontainer mine-item-display" @click="itemClicked('订单')">
              <span>我的订单</span>
              <i class="el-icon-arrow-right"></i>
            </div>
            <div class="mine-item-blank"></div>
            <div class="hcontainer mine-item-display" @click="itemClicked('提现')">
              <span>我要提现</span>
              <i class="el-icon-arrow-right"></i>
            </div>
            <div class="mine-item-blank"></div>
            <div class="hcontainer mine-item-display" @click="itemClicked('帮助')">
              <span>帮助</span>
              <i class="el-icon-arrow-right"></i>
            </div>
            <div class="fill vcontainer mine-btn-container">
              <button class="mine-logout-btn" @click="logoutClicked">退出登录</button>
            </div>
            <!-- 提现未绑卡提示绑卡 -->
            <el-dialog title="请先绑定银行卡" :visible.sync="dialogFormVisible" width="80%" center>
              <el-form :model="bandform" ref="bandForm" :rules="bandrules" status-icon label-position="left">
                <el-form-item label="姓名" :label-width="formLabelWidth" prop="name">
                  <el-input v-model="bandform.name" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item label="开户行" :label-width="formLabelWidth" prop="bank">
                  <el-input v-model="bandform.bank" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item label="卡号" :label-width="formLabelWidth" prop="card">
                  <el-input v-model.number="bandform.card" autocomplete="off"></el-input>
                </el-form-item>
              </el-form>
              <div slot="footer" class="dialog-footer">
                <el-button @click="cancelBandClicked">取 消</el-button>
                <el-button type="primary" @click="confirmBandClicked">确 定</el-button>
              </div>
            </el-dialog>
          </div>
          <div class="fill vcontainer minedetail-display"
            v-loading="mineloading"
            element-loading-text="正在查询中......"
            v-show="!showmineitem">
            <div class="hcontainer mineheader-display">
              <div class="fill">
                <span @click="showmineitem=true"><i class="el-icon-arrow-left"></i>返回</span>
              </div>
              <span class="mineheader-title fill">{{minetitle}}</span>
              <span class="fill"></span>
            </div>
            <textarea class="minedetail-result" v-model="mineresult" disabled="disabled"></textarea>
            <button
              class="minedetail-copybtn"
              data-clipboard-action="copy"
              :data-clipboard-text="minecopyresult">
              复制
            </button>
            <span class="minedetail-tip">{{minetip}}</span>
          </div>
        </div>
      </div>

      <!-- 底部 -->
      <el-menu
        :default-active="activeIndex"
        class="tabbar-display hcontainer"
        mode="horizontal"
        @select="handleSelect"
        menu-trigger="click"
        background-color="#f1f1f3"
        text-color="#000000"
        active-text-color="#457A2C">
        <el-menu-item index="home" class="fill vcontainer tabbar-item">
          <template slot="title">
            <i class="el-icon-search"></i>
            <span class="tabbar-item-title">首页</span>
          </template>
        </el-menu-item>
        <el-menu-item index="mine" class="fill vcontainer tabbar-item">
          <template slot="title">
            <i class="el-icon-setting"></i>
            <span class="tabbar-item-title">个人中心</span>
          </template>
        </el-menu-item>
      </el-menu>
    </div>
    <script>
    var app = new Vue({
      el: '#main',
      data: {
        searchmsg: '',
        searchresult: '',
        copymsg: '',
        tip: '',
        searching: false,
        showresult: false,
        showmineitem: true,
        activeIndex: 'home',
        mineresult: '',
        minecopyresult: '',
        minetip: '',
        mineloading: false,
        minetitle: '',
        dialogFormVisible: false,
        bandform: {
          name: '',
          bank: '',
          card: ''
        },
        formLabelWidth: '70px',
        bandrules: {
          name: [
            {required: true, message: '请填写您的姓名', trigger: 'blur'},
            {min: 2, max: 6, message: '姓名长度为2到6', trigger: 'blur'}
          ],
          bank: [{required: true, message: '请填写您的银行开户行', trigger: 'blur'}],
          card: [
            {required: true, message: '请填写您的银行卡号', trigger: 'blur'},
            {type: 'number', message: '银行卡号必须为数字值'}
          ]
        },
        picUrl: '',
        shortUrl: ''
      },
      computed: {
        welcome () {
          return '欢迎您，' + uid
        }
      },
      methods: {
        handleSelect (key, keyPath) {
          if (this.activeIndex == key) {
            return;
          }
          this.activeIndex = key;
          if (key == 'home') {
            this.showresult = false;
          } else if (key == 'mine') {
            this.showmineitem  = true;
            this.dialogFormVisible = false;
          }
        },
        searchClicked () {
          if (!this.searchmsg) {
            this.$message({
              message: '请在文本框中输入或粘贴内容！',
              type: 'warning',
              center: true,
              duration: 3 * 1000
            });
            return
          }
          this.searching = true;
          this.picUrl = '';
          this.shortUrl = '';
          queryByMsg(this.searchmsg).then((resp) => {
            this.searching = false;
            this.showresult = true;
            this.searchmsg = '';
            let respData = resp;
            if (respData) {
              if (respData.result) {
                this.picUrl = respData.result.pic;
                this.shortUrl = respData.result.short_url;
              }

              switch (parseInt(respData.type)) {
                case 2:
                case 3:
                case 8:
                  this.tip = respData.tip;
                  this.searchresult = respData.msg;
                  this.copymsg = respData.result.tkl;
                  break
                default:
                  this.tip = '';
                  this.searchresult = respData.msg;
                  this.copymsg = respData.msg;
                  break
              }
            } else {
              this.tip = '';
              this.searchresult = '对不起，没有找到对应的数据!';
              this.copymsg = '对不起，没有找到对应的数据!';
            }
          }).catch(error => {
            this.searching = false;
            this.showresult = true;
            this.searchmsg = '';
            this.tip = '';
            this.searchresult = error;
            this.copymsg = error;
          })
        },
        itemClicked (item) {
          this.mineloading = true;
          queryByMsg(item).then((resp) => {
            this.mineloading = false;
            let respData = resp;
            if (item == '提现' && respData && parseInt(respData.type) == 8) {
              this.dialogFormVisible = true;
              return;
            }

            this.showmineitem = false;
            this.minetitle = item;
            if (respData) {
              switch (parseInt(respData.type)) {
                case 2:
                case 3:
                case 8:
                  this.minetip = respData.tip;
                  this.mineresult = respData.msg;
                  this.minecopyresult = respData.result.tkl;
                  break
                default:
                  this.minetip = '';
                  this.mineresult = respData.msg;
                  this.minecopyresult = respData.msg;
                  break
              }
            } else {
              this.minetip = '';
              this.mineresult = '对不起，没有找到对应的数据!';
              this.minecopyresult = '对不起，没有找到对应的数据!';
            }
          }).catch(error => {
            this.showmineitem = false;
            this.minetitle = item;

            this.mineloading = false;
            this.minetip = '';
            this.mineresult = error;
            this.minecopyresult = error;
          });
        },
        cancelBandClicked () {
          this.dialogFormVisible = false;
        },
        confirmBandClicked () {
          var that = this;
          this.$refs.bandForm.validate((valid) => {
            if (valid) {
              that.dialogFormVisible = false;
              var bdmsg = 'bd#' + that.bandform.name + '#' + that.bandform.bank + '#' + that.bandform.card;

              that.showmineitem = false;
              that.minetitle = '提现';
              queryByMsg(bdmsg).then((resp) => {
                let respData = resp;
                if (respData) {
                  switch (parseInt(respData.type)) {
                    case 2:
                    case 3:
                    case 8:
                      that.minetip = respData.tip;
                      that.mineresult = respData.msg;
                      that.minecopyresult = respData.result.tkl;
                      break
                    default:
                      that.minetip = '';
                      that.mineresult = respData.msg;
                      that.minecopyresult = respData.msg;
                      break
                  }
                } else {
                  that.minetip = '';
                  that.mineresult = '对不起，绑定银行卡失败!';
                  that.minecopyresult = '对不起，绑定银行卡失败!';
                }
              }).catch(error => {
                that.minetip = '';
                that.mineresult = '对不起，绑定银行卡失败';
                that.minecopyresult = '对不起，绑定银行卡失败';
              });
            } else {
              return false;
            }
          });
        },
        logoutClicked () {
          $.removeCookie('cookie_uid');
          logout();
        },
        initWithClassName (className) {
          let clipboardBtn = new ClipboardJS(className);

          clipboardBtn.on('success', e => {
            this.$message({
              message: '复制成功',
              type: 'success',
              center: true,
              duration: 2 * 1000
            });
          });

          clipboardBtn.on('error', e => {
            this.$message({
              message: '复制失败',
              type: 'error',
              center: true,
              duration: 2 * 1000
            });
          });
        }
      },
      mounted () {
        this.initWithClassName('.search-copybtn');
        this.initWithClassName('.minedetail-copybtn');
      }
    });
    </script>
  </body>
</html>