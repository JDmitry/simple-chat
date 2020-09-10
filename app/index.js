const app = new Vue({
  el: '#app',
  data: {
    name: null,
    messages: [],
    newName: null,
    newMessage: null,
    isFetching: false,
    refreshInterval: null
  },
  computed: {
    latestMessageID: function () {
      const length = this.messages.length;
      if(length) {
        return this.messages[length - 1].id;
      } else {
        return 0;
      }
    }
  },
  mounted() {
    if (localStorage.name) {
      this.name = localStorage.name;
    }
    if(this.name) {
      this.beginRefresh();
    }
  },
  methods: {
    beginRefresh() {
      this.refresh();
      this.refreshInterval = setInterval(() => {
        this.refresh();
      }, 10000);
    },
    endRefresh() {
      if(this.refreshInterval) {
        clearTimeout(this.refreshInterval);
        this.refreshInterval = null;
      }
    },
    enter() {
      if(!this.newName) { return; }
      localStorage.name = this.newName;
      this.name = this.newName;
      this.newName = null;
      this.beginRefresh();
    },
    exit() {
      localStorage.removeItem('name');
      this.name = null;
      this.messages = [];
      this.endRefresh();
    },
    refresh() {
      if(!this.isFetching) {
        this.fetch();
      }
    },
    send() {
      if(!this.newMessage) { return; }
      const params = new URLSearchParams();
      params.append('sender', this.name);
      params.append('text', this.newMessage);
      axios.post('send.php', params)
        .then(response => {
          const message = response.data;
          if(message.id == this.latestMessageID + 1) {
          	this.messages.push(message);
          } else {
        	this.refresh();
          }
        })
        .catch(error => {
          console.log(error);
        });
      this.newMessage = null;
    },
    fetch() {
      this.isFetching = true;
      axios.get('history.php', {
        params: {
          after: this.latestMessageID
        }
      })
      .then(response => {
        if (response.data.length) {
          this.messages = this.messages.concat(response.data);
        }
      })
      .catch(error => {
        console.log(error);
      })
      .finally(() => {
        this.isFetching = false
      });
    }
  }
})
