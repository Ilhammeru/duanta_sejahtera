<table class="table table-striped">
    <thead >
        <tr class="table-primary">
            <th class="text-center">Container</th>
            <th>Seal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($containers as $container)
            <tr class="text-center">
                <td>{{ $container->container_number }}</td>
                <td>{{ $container->container_seal }}</td>
            </tr>
        @endforeach
    </tbody>
</table>