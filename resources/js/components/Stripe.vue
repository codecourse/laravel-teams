<template>
    <div>
        <div id="card-element" class="mb-2"></div>
        <div id="card-errors"></div>

        <input type="hidden" name="token" :value="token">
    </div>
</template>

<script>
    export default {
        data () {
            return {
                token: null
            }
        },

        mounted () {
            let stripe = Stripe('pk_test_kjzrYyFONSIQLpg57n1R7QxZ')
            let elements = stripe.elements()

            var card = elements.create('card', {
                style: {
                    base: {
                        fontSize: '14px',
                        color: '#32325d'
                    }
                }
            })

            card.addEventListener('change', (event) => {
                let displayError = document.getElementById('card-errors')

                if (event.error) {
                    displayError.textContent = event.error.message
                } else {
                    if (event.complete) {
                        stripe.createToken(card).then((result) => {
                            if (result.error) {
                                displayError.textContent = result.error.message
                            } else {
                                this.token = result.token.id
                            }
                        })
                    }
                }
            });

            card.mount('#card-element')
        }
    }
</script>
