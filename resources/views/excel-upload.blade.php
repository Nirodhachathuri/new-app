<form action="{{ route('import.import') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="excel_file">Choose an Excel File:</label>
    <input type="file" name="excel_file" accept=".xlsx,.xls">
    <button type="submit">Upload Excel</button>
</form>
