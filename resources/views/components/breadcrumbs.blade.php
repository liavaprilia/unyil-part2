        @props(['class' => ''])

        @php
            $segments = request()->segments();
            $baseUrl = url('/');
        @endphp

        <nav class="text-sm text-gray-600 {{ $class }}">
            <ol class="inline-flex p-0 space-x-1 list-none">
                <li class="flex items-center">
                    <a href="{{ url('/') }}" class="text-gray-700 hover:underline">Home</a>
                </li>

                @foreach ($segments as $index => $segment)
                    <li class="flex items-center">
                        <span class="mx-2">/</span>
                        @if ($index + 1 < count($segments))
                            <a href="{{ url(implode('/', array_slice($segments, 0, $index + 1))) }}"
                                class="text-gray-700 hover:underline">
                                {{ ucfirst(str_replace('-', ' ', $segment)) }}
                            </a>
                        @else
                            <span class="text-gray-700">{{ ucfirst(str_replace('-', ' ', $segment)) }}</span>
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>
