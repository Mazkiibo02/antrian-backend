<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Queue List - Admin Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://unpkg.com/react@18/umd/react.development.js"></script>
    <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div id="queue-list-app"></div>

    <script type="text/babel">
        const { useState, useEffect } = React;

        function QueueList() {
            const [queues, setQueues] = useState([]);
            const [loading, setLoading] = useState(true);
            const [filters, setFilters] = useState({
                search: '',
                date: '',
                status: ''
            });

            const fetchQueues = async () => {
                try {
                    const params = new URLSearchParams(filters);
                    const response = await fetch(`/admin/queues?${params}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    const data = await response.json();
                    setQueues(data.data || []);
                } catch (error) {
                    console.error('Error fetching queues:', error);
                } finally {
                    setLoading(false);
                }
            };

            useEffect(() => {
                fetchQueues();
            }, [filters]);

            const handleFilterChange = (key, value) => {
                setFilters(prev => ({
                    ...prev,
                    [key]: value
                }));
            };

            const getStatusColor = (status) => {
                switch (status) {
                    case 'waiting': return 'bg-yellow-100 text-yellow-800';
                    case 'active': return 'bg-blue-100 text-blue-800';
                    case 'completed': return 'bg-green-100 text-green-800';
                    case 'cancelled': return 'bg-red-100 text-red-800';
                    default: return 'bg-gray-100 text-gray-800';
                }
            };

            const getStatusLabel = (status) => {
                switch (status) {
                    case 'waiting': return 'Menunggu';
                    case 'active': return 'Sedang Diproses';
                    case 'completed': return 'Selesai';
                    case 'cancelled': return 'Dibatalkan';
                    default: return 'Tidak Diketahui';
                }
            };

            return (
                <div className="min-h-screen bg-gray-100">
                    <div className="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                        <div className="bg-white shadow overflow-hidden sm:rounded-md">
                            <div className="px-4 py-5 sm:px-6">
                                <h3 className="text-lg leading-6 font-medium text-gray-900">
                                    Daftar Antrian
                                </h3>
                                
                                {/* Filters */}
                                <div className="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700">
                                            Cari Nama
                                        </label>
                                        <input
                                            type="text"
                                            value={filters.search}
                                            onChange={(e) => handleFilterChange('search', e.target.value)}
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            placeholder="Cari berdasarkan nama..."
                                        />
                                    </div>
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700">
                                            Tanggal
                                        </label>
                                        <input
                                            type="date"
                                            value={filters.date}
                                            onChange={(e) => handleFilterChange('date', e.target.value)}
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        />
                                    </div>
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700">
                                            Status
                                        </label>
                                        <select
                                            value={filters.status}
                                            onChange={(e) => handleFilterChange('status', e.target.value)}
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        >
                                            <option value="">Semua Status</option>
                                            <option value="waiting">Menunggu</option>
                                            <option value="active">Sedang Diproses</option>
                                            <option value="completed">Selesai</option>
                                            <option value="cancelled">Dibatalkan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {loading ? (
                                <div className="px-4 py-8 text-center">
                                    <div className="text-gray-500">Loading...</div>
                                </div>
                            ) : (
                                <ul className="divide-y divide-gray-200">
                                    {queues.map((queue) => (
                                        <li key={queue.id} className="px-4 py-4 sm:px-6">
                                            <div className="flex items-center justify-between">
                                                <div className="flex items-center">
                                                    <div className="flex-shrink-0">
                                                        <div className="text-2xl font-bold text-blue-600">
                                                            {queue.queue_number}
                                                        </div>
                                                    </div>
                                                    <div className="ml-4">
                                                        <div className="text-sm font-medium text-gray-900">
                                                            {queue.name}
                                                        </div>
                                                        <div className="text-sm text-gray-500">
                                                            {new Date(queue.created_at).toLocaleString('id-ID')}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="flex items-center">
                                                    <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusColor(queue.status)}`}>
                                                        {getStatusLabel(queue.status)}
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                    ))}
                                </ul>
                            )}
                        </div>
                    </div>
                </div>
            );
        }

        ReactDOM.render(<QueueList />, document.getElementById('queue-list-app'));
    </script>
</body>
</html> 