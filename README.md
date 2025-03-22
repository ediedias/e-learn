# Thought Process

Planning the overview. What the project will be like. It's an e-learn app for students and professors. Students can log in and choose their classes. Professors can log in and build their classes/lessons. When a student logs in, it will show a list of classes. When he chooses a class, it will show the info about that class with the lessons. He can see the lesson.

**OBS:** Since this is only for demonstration purposes, I will not use API versioning.

Setting up the basic structure:
   - Models: User, Classes, Lessons  
     For each of those we initially are going to need:
     - controller
     - factory
     - migration

For each model, set up the basic column structure and relationships.

Build the login/register feature. We will use Sanctum for that.

Build tests for the register feature first.

Build login tests.

Build a User resource so we can return the user information when necessary (like when we log in). That way, we can select what information we will show to the API.  
At the same time, build a basic payload structure. For that, I'll use the JSON:API specification.

Now that the register and login scaffold is done, I’ll build what the user sees when they log in, which is the course list (the redirection is done by the frontend since we are only building the API for resources).

So I build 1 test (it could be done in two tests since I'm asserting two things): one for testing the "courses show" payload, making sure it returns the CourseResource and that it is paginated.

When I made the controller index method implementation, I made sure I eager loaded the user class so we don't have the N+1 problem.

After that, I moved on to build the course/show. Again, starting with the tests. For this, I used tests that would check if the authenticated user could retrieve a single course, the response containing the correct JSON structure, and a 404 if the course doesn't exist.

The next endpoint I created was the `POST /courses`, where you could store a course. But since only professors could create a post, I also implemented the policy for that. I started with the test first: Professors can create courses, students can't. I also made sure that unauthorized users also couldn't create a course.

The same logic was built toward updating and deleting. Only teachers could delete/update, and it had to be their course.

The same CRUD logic and steps are applied to the lessons. But I only developed the create method because I had personal issues.

I still wanted to implement and test some things, but because of that issue, I couldn't.

---

## What still needed to be done:

- After making all tests and implementing all the logic for it, I would create the endpoints on Postman to make sure the payloads were returning correct formats and data.
- I also thought it would be better for the story that the professor could only see their own classes in the index (it could be done in another route, but I thought it would fit better for this example).
- If a course is deleted, all of the lessons would also be deleted (make a test for that).
- Instead of writing every single payload response, I could standardize it by building a custom macro or base resource.
- Should add the link in CourseResource.
- When showing the course, it should also show the lessons, but not in the index.
