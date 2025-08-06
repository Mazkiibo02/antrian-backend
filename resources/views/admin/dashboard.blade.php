<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Queue Management</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://unpkg.com/react@18/umd/react.development.js"></script>
    <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div id="dashboard-app"></div>

    <script type="text/babel">
        const { useState, useEffect } = React;

        function Dashboard() {
            const [currentQueue, setCurrentQueue] = useState(null);
            const [queues, setQueues] = useState([]);
            const [loading, setLoading] = useState(false);
            const [message, setMessage] = useState('');
            const [filters, setFilters] = useState({
                search: '',
                date: '',
                status: ''
            });

            const fetchCurrentQueue = async () => {
                try {
                    const response = await fetch('/admin/queues/current', {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    const data = await response.json();
                    setCurrentQueue(data.current);
                } catch (error) {
                    console.error('Error fetching current queue:', error);
                }
            };

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
                }
            };

            const handleNext = async () => {
                setLoading(true);
                try {
                    const response = await fetch('/admin/queues/next', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    const data = await response.json();
                    if (data.success) {
                        setMessage(data.message);
                        fetchCurrentQueue();
                        fetchQueues();
                    }
                } catch (error) {
                    setMessage('Error: ' + error.message);
                } finally {
                    setLoading(false);
                }
            };

            const handlePrevious = async () => {
                setLoading(true);
                try {
                    const response = await fetch('/admin/queues/previous', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    const data = await response.json();
                    if (data.success) {
                        setMessage(data.message);
                        fetchCurrentQueue();
                        fetchQueues();
                    }
                } catch (error) {
                    setMessage('Error: ' + error.message);
                } finally {
                    setLoading(false);
                }
            };

            const handleLogout = async () => {
                try {
                    await fetch('/admin/logout', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    window.location.href = '/admin/login';
                } catch (error) {
                    console.error('Error logging out:', error);
                }
            };

            const handleFilterChange = (key, value) => {
                setFilters(prev => ({
                    ...prev,
                    [key]: value
                }));
            };

            useEffect(() => {
                fetchCurrentQueue();
                fetchQueues();
            }, []);

            useEffect(() => {
                fetchQueues();
            }, [filters]);

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
                    {/* Header */}
                    <nav className="bg-white shadow-sm">
                        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <div className="flex justify-between h-16">
                                <div className="flex items-center">
                                    <h1 className="text-xl font-semibold text-gray-900">
                                        Queue Management System
                                    </h1>
                                </div>
                                <div className="flex items-center">
                                    <button
                                        onClick={handleLogout}
                                        className="ml-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700"
                                    >
                                        Logout
                                    </button>
                                </div>
                            </div>
                        </div>
                    </nav>

                    <div className="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                        {message && (
                            <div className="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                                {message}
                            </div>
                        )}

                        {/* Current Queue Section */}
                        <div className="bg-white overflow-hidden shadow rounded-lg mb-6">
                            <div className="px-4 py-5 sm:p-6">
                                <h3 className="text-lg leading-6 font-medium text-gray-900 mb-4">
                                    Antrian Saat Ini
                                </h3>
                                {currentQueue ? (
                                    <div className="text-center">
                                        <div className="text-4xl font-bold text-blue-600 mb-2">
                                            {currentQueue.queue_number}
                                        </div>
                                        <div className="text-xl text-gray-900 mb-4">
                                            {currentQueue.name}
                                        </div>
                                        <div className="flex justify-center space-x-4">
                                            <button
                                                onClick={handlePrevious}
                                                disabled={loading}
                                                className="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 disabled:opacity-50"
                                            >
                                                Previous
                                            </button>
                                            <button
                                                onClick={handleNext}
                                                disabled={loading}
                                                className="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 disabled:opacity-50"
                                            >
                                                Next
                                            </button>
                                        </div>
                                    </div>
                                ) : (
                                    <div className="text-center text-gray-500">
                                        Tidak ada antrian aktif
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* Queue List Section */}
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
                        </div>
                    </div>
                </div>
            );
        }

        ReactDOM.render(<Dashboard />, document.getElementById('dashboard-app'));
    </script>
</body>
</html> 