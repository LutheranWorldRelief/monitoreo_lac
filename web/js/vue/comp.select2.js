Vue.component('combo-select2', {
    template: '<select class="form-control select2-enable"></select>',
    props:['options', 'value', 'prompt'],
    data: function () {
        return {
        };
    },
    methods:{

    },
    mounted: function () {
        var self = this;

        var options = $.map(self.options, function (value, index) {
            return {
                id: index,
                text: value
            };
        });

        options.unshift({
            id: '',
            text: self.prompt || '-- Seleccionar --'
        });

        $(self.$el).select2({
            data:options
        })
            .on('change', (e) => {
                self.$emit('input', e.target.value);
            });

        if(self.value) $(self.$el).val(self.value).trigger('change');
    },
    watch: {
        value: function (val, oldVal) {
            var self = this;
            $(self.$el).val(val).trigger('change');
        }
    },
});