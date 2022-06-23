<div class="table-responsive">
    <table class="table table-striped">
        <thead >
            <tr class="text-center table-primary">
                <th>Container</th>
                <th>Seal</th>
                <th>Cargo/Goods</th>
                <th>Volume</th>
                <th>Size/Type</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($containers as $container)
                <tr class="text-center">
                    <td>{{ $container->container_number }}</td>
                    <td>{{ $container->container_seal }}</td>
                    <td>{{ $container->cargo_goods }}</td>
                    <td>{{ $container->volume }}</td>
                    <td>
                        @if ($container->is_customer_container_size)
                            {{ $container->custom_container_size }}
                        @else
                            {{ $container->sizeType->size . '/' . $container->sizeType->type }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>