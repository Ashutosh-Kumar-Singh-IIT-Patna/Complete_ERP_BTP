import pandas as pd

# Load the CSV files
course_master_path = 'php/src/downloads/course_master.csv'  # Replace with your course_master CSV file path
de_course_map_path = 'php/src/downloads/de_course_map.csv'  # Replace with your de_course_map CSV file path

course_master_df = pd.read_csv(course_master_path)
de_course_map_df = pd.read_csv(de_course_map_path)

# Helper function to clean and filter rows based on type
def filter_and_prepare_core_course(df):
    # Ensure 'Type' column is string, strip whitespace, convert to uppercase, and remove empty rows
    df['Type'] = df['Type'].astype(str).str.strip().str.upper()
    
    # Remove rows where 'Type' is empty or NaN
    df = df[df['Type'].notna() & (df['Type'] != '')]
    
    # Select rows where 'Type' does not start with 'IDE', 'HS', or 'DE'
    core_df = df[~df['Type'].str.startswith(('IDE', 'HS', 'DE'))]
    
    # Prepare the core_course DataFrame with the required columns
    core_course_df = pd.DataFrame({
        'roll': core_df['Roll Number'],
        'sem': core_df['Semester #'],
        'course_code': core_df['Course Code'],
        'type': core_df['Type'],
        'floated': 1,  # Default value
        'offered_for_minor': core_df['offered_for_minor'],
        'fac_empid': None,
        'fac_masterid': None,
        'comment1': None,
        'comment2': None
    })
    
    return core_course_df

# Helper function to filter rows for elective_map
def filter_and_prepare_elective_map(df1, df2):
    # Ensure 'Type' column is string, strip whitespace, convert to uppercase, and remove empty rows
    df1['Type'] = df1['Type'].astype(str).str.strip().str.upper()
    df2['Type'] = df2['Type'].astype(str).str.strip().str.upper()
    
    # Select rows where 'Type' starts with 'IDE', 'HS', or 'DE' in course_master
    elective_df1 = df1[df1['Type'].str.startswith(('IDE', 'HS', 'DE'))]
    
    # Prepare the elective_map DataFrame with the required columns from course_master
    elective_map_df1 = pd.DataFrame({
        'roll': elective_df1['Roll Number'],
        'sem': elective_df1['Semester #'],
        'course_code': elective_df1['Course Code'],
        'type': elective_df1['Type'],
        'floated': 0,  # Default value for elective courses
        'fac_empid': None,
        'fac_masterid': None,
        'max_capacity': 130,  # Default value
        'comment1': None,
        'comment2': None
    })
    
    # Select rows where 'Type' starts with 'IDE', 'HS', or 'DE' in de_course_map
    elective_df2 = df2[df2['Type'].str.startswith(('IDE', 'HS', 'DE'))]
    
    # Prepare the elective_map DataFrame with the required columns from de_course_map
    elective_map_df2 = pd.DataFrame({
        'roll': elective_df2['Roll Number'],
        'sem': elective_df2['Semester #'],
        'course_code': elective_df2['Course Code'],
        'type': elective_df2['Type'],
        'floated': 0,  # Default value for elective courses
        'fac_empid': None,
        'fac_masterid': None,
        'max_capacity': 130,  # Default value
        'comment1': None,
        'comment2': None
    })

    # Concatenate the two dataframes into the final elective_map dataframe
    elective_map_df = pd.concat([elective_map_df1, elective_map_df2], ignore_index=True)
    
    return elective_map_df

# Generate the core_course DataFrame
core_course_df = filter_and_prepare_core_course(course_master_df)

# Generate the elective_map DataFrame
elective_map_df = filter_and_prepare_elective_map(course_master_df, de_course_map_df)

# Save the DataFrames to new CSV files
core_course_output = 'php/src/downloads/core_course.csv'
elective_map_output = 'php/src/downloads/elective_map.csv'

core_course_df.to_csv(core_course_output, index=False)
elective_map_df.to_csv(elective_map_output, index=False)

core_course_output, elective_map_output
