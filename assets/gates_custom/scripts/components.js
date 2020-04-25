let x, y, z;

const Foods = Vue.component('foods', {
    props: ['connect'],
    template: '#foods-template',
    data: function() {
        return {
            foods__items: {
                items: [],
                pagination: {
                    start: 0,
                    currentPage: 0,
                    pages: [],
                    per_page: 12
                },
                filter: false,
                sortByValues: {
                    column: 'date_added',
                    type: '1',
                    row: 'DESC',
                },
                //РџРѕРёСЃРє РїРѕ РёРјРµРЅРё
                searchByName: '',
                loading: false,
                firstLoading: true
            }
        }
    },
    computed: {
        searchByNameFoodsWatch() {
            return this.foods__items.searchByName;
        }
    },
    watch: {
        searchByNameFoodsWatch() {
            let vm = this;
            clearTimeout(y);

            y = setTimeout(function() {
                vm.foods__loadList(0);
            }, 400);
        },
    },
    created: function() {
        this.initFoods();
    },
    methods: {
        initFoods() {
            this.foods__loadList(this.foods__items.pagination.start);
        },
        foods__loadList(page) {



            var vm_items = this.foods__items;
            vm_items.loading = true;
            vm_items.pagination.currentPage = page / vm_items.pagination.per_page;
            vm_items.pagination.start = page;

            if (vm_items.searchByName) {
                filter_name = vm_items.searchByName;
            }

            let params = {
                page: vm_items.pagination.start,
                filter_name: vm_items.searchByName,
                sort_by_value: vm_items.sortByValues,
                connect: this.connect,
            };

            if (params.filter_name !== '') {
                vm_items.filter = true;
            }


            axios.post('/foods/getAllItems/', 'get=foods' + '&params=' + JSON.stringify(params), {
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    }
                })
                .then(response => {
                    vm_items.items = response.data.items;
                    vm_items.pagination.pages = response.data.pagination;
                    vm_items.loading = false;

                    if (vm_items.firstLoading == false) {
                        $('html, body').animate({
                            scrollTop: $('#list').offset().top
                        }, 0);
                    }

                    vm_items.firstLoading = false;

                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        foods__changePage(ev) {
            ev.preventDefault();
            let page = ev.currentTarget.getAttribute('data-start');
            this.foods__loadList(parseInt(page));
        },
        foods__sortIt(ev) {
            var vm = this;
            var vm_items = this.foods__items;

            vm_items.sortByValues.column = ev.target.value;
            vm_items.sortByValues.type = ev.target.options[ev.target.selectedIndex].dataset.type;

            vm.foods__loadList(0);
        },
        foods__changeSortRow(ev) {
            var vm = this;
            var vm_items = this.foods__items;

            if (vm_items.sortByValues.row == 'DESC') {
                vm_items.sortByValues.row = 'ASC';
            } else {
                vm_items.sortByValues.row = 'DESC';
            }

            vm.foods__loadList(0);
        },
    },
})

let contactForm = Vue.component('contact', {
    data: () => {
        return {
            contactForm: {},
            contactFormErrors: {
                name: '',
                email: '',
                phone_number: '',
                message: '',
            },
            loading: false
        }
    },
    methods: {

        sendIt() {
            var params = this.contactForm;
            this.loading = true;

            axios.post('/home/contactForm/', 'q=contactForm' + '&params=' + JSON.stringify(params), {
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    }
                })
                .then(response => {
                    if (response.data.status) {
                        this.contactFormErrors = {};
                        this.contactForm = {};

                        let nParams = {
                            title: "",
                            text: response.data.msg,
                            type: "success",
                            timeout: 6000
                        };
                        this.$root.showNotification(nParams);


                    } else {
                        for (var prop in response.data.errors) {
                            this.contactFormErrors[prop] = response.data.errors[prop];
                        }
                    }
                    this.loading = false;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
    },
})

const Partners = Vue.component('partners', {
    props: ['connect'],
    template: '#partners-template',
    data: function() {
        return {
            partners__items: {
                items: [],
                pagination: {
                    start: 0,
                    currentPage: 0,
                    pages: [],
                },
                filter: false,
                sortByValues: {
                    column: 'date_added',
                    type: '1',
                    row: 'DESC',
                },
                //Поиск по имени
                searchByName: '',
                loading: true,
            }
        }
    },
    computed: {
        searchByNamePartnersWatch() {
            return this.partners__items.searchByName;
        }
    },
    watch: {
        searchByNamePartnersWatch() {
            let vm = this;
            clearTimeout(y);

            y = setTimeout(function() {
                vm.partners__loadList(0);
            }, 400);
        },
    },
    created: function() {
        this.partners__items.loading = false;
        this.initPartners();
    },
    methods: {
        initPartners() {
            this.partners__loadList(this.partners__items.pagination.start);
        },
        partners__loadList(page) {
            var vm = this;
            var vm_items = this.partners__items;
            vm_items.loading = true;

            vm_items.pagination.start = page;

            //Поиск
            let filter_name = '';
            if (vm_items.searchByName) {
                filter_name = vm_items.searchByName;
            }

            let params = {
                page: vm_items.pagination.start,
                filter_name: vm_items.searchByName,
                sort_by_value: vm_items.sortByValues,
            };

            if (params.filter_name !== '') {
                vm_items.filter = true;
            }


            axios.post('/partners/getAllItems/', 'get=partners' + '&params=' + JSON.stringify(params), {
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    }
                })
                .then(response => {

                    vm_items.items = response.data.items;
                    vm_items.pagination.pages = response.data.pagination;


                    setTimeout(() => vm_items.loading = false, 500);

                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        partners__changePage(ev) {
            ev.preventDefault();

            var vm_items = this.partners__items;

            let page = ev.currentTarget.getAttribute('data-start');
            vm_items.pagination.currentPage = page;
            this.partners__loadList(page);
        },
        //Сортировка
        partners__sortIt(ev) {
            var vm = this;
            var vm_items = this.partners__items;

            vm_items.sortByValues.column = ev.target.value;
            vm_items.sortByValues.type = ev.target.options[ev.target.selectedIndex].dataset.type;

            vm.partners__loadList(0);
        },
        //Сортировка - Стрелки
        partners__changeSortRow(ev) {
            var vm = this;
            var vm_items = this.partners__items;

            if (vm_items.sortByValues.row == 'DESC') {
                vm_items.sortByValues.row = 'ASC';
            } else {
                vm_items.sortByValues.row = 'DESC';
            }

            vm.partners__loadList(0);
        },
    },
})

/* Fixed components */
const Modal = {
    name: 'modal',
    template: '#modal',
    methods: {
        close(event) {
            this.$emit('close');
        },
    },
};


const Notifications = Vue.component('notifications', {
    name: 'notifications',
    template: '#notifications-template',
    data: function() {
        return {}
    },
})

const Notification = Vue.component('notification', {
    props: ['notification'],
    name: 'notification',
    template: '#notification-template',
    data: function() {
        return {
            timer: ''
        }
    },
    created: function() {
        let timeout = this.notification.timeout ? this.notification.timeout : false;
        if (timeout) {
            let delay = this.notification.timeout ? this.notification.timeout : 4000
            this.timer = setTimeout(function() {
                this.$root.notifications.splice(this.$root.notifications.indexOf(this.notification), 1);
            }.bind(this), delay)
        }
    },
});


/* comments */
const Comments = Vue.component('comments', {
    props: ['section_id', 'item_id', 'is_item', 'user_name', 'user_email'],
    name: 'comments',
    template: '#comments-template',
    data: function() {
        return {
            comments__items: {
                items: [],
                pagination: {
                    start: 0,
                    per_page: 12,
                },
                filter: false,
                sortByValues: {
                    column: 'date_added',
                    type: '1',
                    row: 'DESC',
                },
                loading: true,
                loadMore: {
                    count: 0,
                    loading: false,
                }
            },
            commentAddForm: {
                rate: 3,
            },
            addError: {
                name: '',
                email: '',
                text: '',
            }
        }
    },
    created() {
        this.commentAddForm.name = this.user_name;
        this.commentAddForm.email = this.user_email;
        this.initComments();
    },
    methods: {
        initComments() {
            this.getComments(0);
        },
        getComments(page, add = false) {
            var vm = this;
            var vm_items = this.comments__items;

            if (add) {
                // vm_items.loading = true;
            }

            vm_items.loadMore.loading = true;

            vm_items.pagination.start = page;

            let params = {
                section_id: this.section_id,
                item_id: this.item_id,
                is_item: this.is_item,
                page: vm_items.pagination.start,
                per_page: vm_items.pagination.per_page,
                sort_by_value: vm_items.sortByValues,
            };

            axios.post('/reviews/getComments/', 'get=' + 'comments' + '&params=' + JSON.stringify(params), {
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    }
                })
                .then(response => {
                    vm_items.loadMore.count = response.data.next_items;

                    if (!add) {
                        if (response.data.items.length > 0) {
                            for (let i = 0; i < response.data.items.length; i++) {
                                vm_items.items.push(response.data.items[i]);
                            }
                        }
                    } else {
                        vm_items.items = response.data.items;
                    }


                    vm_items.loading = false;
                    vm_items.loadMore.loading = false;


                })
        },
        comments__changePage(ev) {
            ev.preventDefault();

            var vm_items = this.comments__items;

            let page = ev.currentTarget.getAttribute('data-start');

            this.getComments(vm_items.pagination.start + vm_items.pagination.per_page);

        },
        //Сортировка
        comments__sortIt(ev) {
            var vm = this;
            var vm_items = this.comments__items;

            vm_items.sortByValues.column = ev.target.value;
            vm_items.sortByValues.type = ev.target.options[ev.target.selectedIndex].dataset.type;

            vm.getComments(0, true);
        },
        //Сортировка - Стрелки
        comments__changeSortRow(ev) {
            var vm = this;
            var vm_items = this.comments__items;

            if (vm_items.sortByValues.row == 'DESC') {
                vm_items.sortByValues.row = 'ASC';
            } else {
                vm_items.sortByValues.row = 'DESC';
            }

            vm.getComments(0, true);
        },

        sendComment(ev) {
            ev.preventDefault();

            this.commentAddForm.section_id = this.section_id;
            this.commentAddForm.item_id = this.item_id;
            this.commentAddForm.is_item = this.is_item;

            let params = this.commentAddForm;

            axios.post('/reviews/sendComment/', 'q=' + 'sendComment' + '&params=' + JSON.stringify(params), {
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    }
                })
                .then(response => {

                    if (response.data.status) {
                        this.addError = {};
                        this.commentAddForm.text = '';

                        this.getComments(0, true);

                        let nParams = {
                            title: "",
                            text: response.data.msg,
                            type: "success",
                            timeout: 6000
                        };
                        this.$root.showNotification(nParams);
                    } else {
                        for (var prop in response.data.errors) {
                            this.addError[prop] = response.data.errors[prop];
                        }
                    }

                })
        },
        changeRate(ev) {
            this.commentAddForm.rate = parseInt(ev.currentTarget.dataset.val);
        }
    },
})

const Comment = Vue.component('comment', {
    props: ['comment'],
    name: 'comment',
    template: '#comment-template',
    data: function() {
        return {}
    },
    created: function() {

    },
    methods: function() {

    }
})