<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight"
            style="background: {{ $counter->color }}; color: #fff; padding: 0.5rem;">
            {{ __('Counter') }} {{ $counter->name }} Queue
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <ul id="queue">
            </ul>
        </div>
    </div>
    <template id="ticket-template">
        <li class="queue-ticket">
            <table>
                <tbody>
                    <tr>
                        <td>Number: </td>
                        <td class="ticket-number"></td>
                    </tr>
                    <tr>
                        <td>Customer Name:</td>
                        <td class="ticket-customer-name"></td>
                    </tr>
                    <tr>
                        <td>Taking at:</td>
                        <td class="ticket-created-at"></td>
                    </tr>
                </tbody>
            </table>
            <div>
                <x-danger-button class="ms-3 ticket-pick-btn"> {{ __('Pick') }} </x-danger-button>
                <x-danger-button class="ticket-cancel-btn">Cancel</x-danger-button>
                <x-danger-button class="ticket-close-btn">Close</x-danger-button>
            </div>
        </li>
    </template>
    @section('js')
        <script>
            const _token = "{{ csrf_token() }}";
            const queueEle = document.getElementById('queue');
            let updateQueueElementTimeout;
            async function updateQueueElement() {
                try {
                    const result = await fetch("{{ route('admin.queue', $counter->id) }}");
                    const newQueue = await result.json();
                    const updatedQueue = [];
                    const currentTicketIds = [...document.getElementsByClassName('queue-ticket')].map(a => parseInt(a
                        .getAttribute('data-ticket-id')));
                    newQueue.forEach(({
                        id: ticketId,
                        status: ticketStatus,
                        number: ticketNumber,
                        customer: {
                            name: customerName
                        },
                        created_at: ticketCreatedAt,
                        diff_now: ticketDiffNow
                    }) => {
                        const foundTicketElement = document.querySelector(
                            `.queue-ticket[data-ticket-id='${ticketId}']`);
                        if (foundTicketElement) {
                            updateTicketElement({
                                ticketId,
                                ticketStatus,
                                ticketNumber,
                                customerName,
                                ticketCreatedAt,
                                ticketDiffNow,
                            });
                            currentTicketIds.splice(currentTicketIds.indexOf(ticketId), 1);
                        } else {
                            queueEle.append(addTicketElement({
                                ticketId,
                                ticketStatus,
                                ticketNumber,
                                customerName,
                                ticketCreatedAt,
                                ticketDiffNow,
                            }))
                        }
                    });
                    currentTicketIds.forEach(a => document.querySelector(
                        `.queue-ticket[data-ticket-id='${a}']`).remove());
                } catch (error) {
                    console.error(error);
                } finally {
                    updateQueueElementTimeout = setTimeout(updateQueueElement, 1000);
                }
            }

            function updateTicketElement({
                ticketId,
                ticketStatus,
                ticketNumber,
                customerName,
                ticketCreatedAt,
                ticketDiffNow
            }) {
                const ticketElement = document.querySelector(
                    `.queue-ticket[data-ticket-id='${ticketId}']`);
                const pickBtn = ticketElement.querySelector('.ticket-pick-btn');
                const cancelBtn = ticketElement.querySelector('.ticket-cancel-btn');
                const closeBtn = ticketElement.querySelector('.ticket-close-btn');
                ticketElement.querySelector('.ticket-created-at').innerHTML =
                    `${ticketCreatedAt} (${ticketDiffNow})`;
                updateTicketElementButton({
                    ticketElement,
                    pickBtn,
                    closeBtn,
                }, {
                    ticketId,
                    ticketStatus,
                    ticketNumber,
                    customerName,
                    ticketCreatedAt,
                    ticketDiffNow
                });
                if (ticketStatus === 'processing') {
                    ticketElement.classList.add('picking');
                    pickBtn.classList.add('hidden-ticket-btn');
                    closeBtn.classList.remove('hidden-ticket-btn');
                } else if (ticketStatus === 'open') {
                    pickBtn.classList.remove('hidden-ticket-btn');
                    closeBtn.classList.add('hidden-ticket-btn');
                }
            }

            function updateTicketElementButton({
                ticketElement,
                pickBtn,
                closeBtn,
            }, {
                ticketId,
                ticketStatus,
                ticketNumber,
                customerName,
                ticketCreatedAt,
                ticketDiffNow
            }) {
                if (ticketStatus === 'processing') {
                    ticketElement.classList.add('picking');
                    pickBtn.classList.add('hidden-ticket-btn');
                    closeBtn.classList.remove('hidden-ticket-btn');
                } else if (ticketStatus === 'open') {
                    pickBtn.classList.remove('hidden-ticket-btn');
                    closeBtn.classList.add('hidden-ticket-btn');
                }
            }

            function addTicketElement({
                ticketId,
                ticketStatus,
                ticketNumber,
                customerName,
                ticketCreatedAt,
                ticketDiffNow
            }) {
                const ticketTemplate = document.getElementById('ticket-template');
                const newTicketEle = ticketTemplate.content.cloneNode(true);
                const li = newTicketEle.querySelector('li');
                const pickBtn = newTicketEle.querySelector('.ticket-pick-btn');
                const cancelBtn = newTicketEle.querySelector('.ticket-cancel-btn');
                const closeBtn = newTicketEle.querySelector('.ticket-close-btn');
                li.setAttribute('data-ticket-id', ticketId);
                li.setAttribute('data-ticket-status', ticketStatus);
                newTicketEle.querySelector('.ticket-number').innerHTML = ticketNumber;
                newTicketEle.querySelector('.ticket-customer-name').innerHTML = customerName;
                newTicketEle.querySelector('.ticket-created-at').innerHTML =
                    `${ticketCreatedAt} (${ticketDiffNow})`;
                updateTicketElementButton({
                    ticketElement: li,
                    pickBtn,
                    closeBtn,
                }, {
                    ticketId,
                    ticketStatus,
                    ticketNumber,
                    customerName,
                    ticketCreatedAt,
                    ticketDiffNow
                });
                pickBtn.addEventListener('click', (e) => changeTicketStatusHandler(ticketId, 'pick'));
                cancelBtn.addEventListener('click', (e) => changeTicketStatusHandler(ticketId, 'cancel'));
                closeBtn.addEventListener('click', (e) => changeTicketStatusHandler(ticketId, 'close'));
                return newTicketEle;
            }

            async function changeTicketStatusHandler(ticketId, action) {
                try {
                    const result = await fetch("{{ route('admin.ticket.action') }}", {
                        method: 'PUT',
                        body: JSON.stringify({
                            action,
                            ticket_id: ticketId,
                            _token,
                        }),
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': _token,
                        },
                    });
                    const data = await result.json();
                } catch (error) {
                    console.error(error);
                } finally {
                    clearTimeout(updateQueueElementTimeout);
                    updateQueueElement();
                }
            }

            updateQueueElement();
        </script>
    @endsection
</x-app-layout>
