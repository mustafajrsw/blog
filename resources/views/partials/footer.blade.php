<footer class="bg-white border-t border-gray-200 mt-10">
    <div
        class="container mx-auto px-4 py-6 flex flex-col sm:flex-row justify-between items-center text-sm text-gray-600">
        <p>&copy; {{ date('Y') }} {{ config('app.name', 'My Blog') }}. All rights reserved.</p>
        <div class="flex space-x-4 mt-3 sm:mt-0">
            <a href="#" class="hover:text-blue-600">Twitter</a>
            <a href="#" class="hover:text-blue-600">GitHub</a>
            <a href="#" class="hover:text-blue-600">LinkedIn</a>
        </div>
    </div>
</footer>
