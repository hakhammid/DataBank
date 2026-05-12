<div class="py-12 mt-[5rem] min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Download History</h1>
                <p class="text-gray-600 mt-1">Review the materials you've accessed</p>
            </div>
            
            <!-- Quota Indicator -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
                <div class="p-3 rounded-lg {{ $remainingQuota > 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Daily Quota</p>
                    <p class="text-lg font-bold {{ $remainingQuota > 0 ? 'text-gray-900' : 'text-red-600' }}">
                        {{ $remainingQuota }} / 5 Remaining
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
            <div class="p-6 bg-white border-b border-gray-200">
                @if($downloads->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 rounded-l-lg">Module Title</th>
                                    <th scope="col" class="px-6 py-3">Course Code</th>
                                    <th scope="col" class="px-6 py-3">Faculty</th>
                                    <th scope="col" class="px-6 py-3">Downloaded At</th>
                                    <th scope="col" class="px-6 py-3 rounded-r-lg text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($downloads as $download)
                                    <tr class="bg-white border-b hover:bg-gray-50 transition-colors">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                            {{ $download->module->title ?? 'N/A' }}
                                        </th>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-md font-semibold text-xs">
                                                {{ $download->module->course_code ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $download->module->user->name ?? 'Unknown' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ \Carbon\Carbon::parse($download->downloaded_at)->format('M d, Y h:i A') }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($download->module)
                                                <a href="{{ route('view-module', $download->module) }}" class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium text-white bg-zinc-900 border border-transparent rounded-lg hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-zinc-900 transition-colors">
                                                    View
                                                </a>
                                            @else
                                                <span class="text-red-500 text-xs font-semibold">Unavailable</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6">
                        {{ $downloads->links() }}
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-12">
                        <div class="p-4 bg-gray-50 rounded-full mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">No Download History</h3>
                        <p class="mt-1 text-gray-500 text-sm max-w-sm text-center">You haven't downloaded any modules yet. When you do, they will appear here for easy access later.</p>
                        <a href="{{ route('student') }}" class="mt-6 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-zinc-900 hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-zinc-900">
                            Browse Modules
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
